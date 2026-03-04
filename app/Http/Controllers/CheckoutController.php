<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    // Shipping rates matching the policy
    protected function shippingRate(string $method, float $subtotal): float
    {
        return match($method) {
            'uk_dpd'      => $subtotal >= 80 ? 0 : 5.50,
            'intl_europe' => 12.50,
            'intl_world'  => 12.50,
            default       => 5.50,
        };
    }

    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // ── Show checkout page + create PaymentIntent ──
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your bag is empty.');
        }

        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shipping = $this->shippingRate('uk_dpd', $subtotal); // default to UK
        $total    = round(($subtotal + $shipping) * 100);     // Stripe uses pence

        $intent = PaymentIntent::create([
            'amount'   => $total,
            'currency' => 'gbp',
            'metadata' => [
                'user_id'    => auth()->id(),
                'user_email' => auth()->user()->email,
            ],
        ]);

        session()->put('stripe_payment_intent_id', $intent->id);

        return view('checkout', [
            'clientSecret' => $intent->client_secret,
            'stripeKey'    => config('services.stripe.key'),
            'subtotal'     => $subtotal,
            'shipping'     => $shipping,
            'total'        => $subtotal + $shipping,
        ]);
    }

    // ── Update PaymentIntent when shipping method changes ──
    public function updateIntent(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:uk_dpd,intl_europe,intl_world',
        ]);

        $cart     = session()->get('cart', []);
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shipping = $this->shippingRate($request->shipping_method, $subtotal);
        $total    = round(($subtotal + $shipping) * 100);

        $intentId = session()->get('stripe_payment_intent_id');
        if ($intentId) {
            PaymentIntent::update($intentId, ['amount' => $total]);
        }

        return response()->json([
            'shipping' => $shipping,
            'total'    => $subtotal + $shipping,
        ]);
    }

    // ── Handle form submission after Stripe confirms payment ──
    public function store(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'email'             => 'required|email',
            'phone'             => 'nullable|string|max:30',
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'address_line1'     => 'required|string|max:255',
            'address_line2'     => 'nullable|string|max:255',
            'city'              => 'required|string|max:100',
            'postcode'          => 'required|string|max:20',
            'country'           => 'required|string|max:2',
            'shipping_method'   => 'required|in:uk_dpd,intl_europe,intl_world',
            'notes'             => 'nullable|string|max:500',
        ]);

        // Verify payment with Stripe
        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return back()->withErrors(['payment' => 'Payment was not completed. Please try again.']);
        }

        $cart     = session()->get('cart', []);
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shipping = $this->shippingRate($request->shipping_method, $subtotal);

        $shippingLabels = [
            'uk_dpd'      => 'DPD Delivery (1–2 Days)',
            'intl_europe' => 'European Tracked (4–6 Days)',
            'intl_world'  => 'International Tracked (4–8 Days)',
        ];

        session()->put('order_confirmation', [
            'reference'         => 'DERU-' . strtoupper(substr($intent->id, -8)),
            'payment_intent_id' => $intent->id,
            'name'              => $request->first_name . ' ' . $request->last_name,
            'email'             => $request->email,
            'address'           => implode(', ', array_filter([
                                        $request->address_line1,
                                        $request->address_line2,
                                        $request->city,
                                        $request->postcode,
                                        $request->country,
                                    ])),
            'shipping_method'   => $shippingLabels[$request->shipping_method],
            'shipping_cost'     => $shipping,
            'subtotal'          => $subtotal,
            'total'             => $subtotal + $shipping,
            'items'             => $cart,
            'notes'             => $request->notes,
            'placed_at'         => now()->format('d M Y, H:i'),
        ]);

        session()->forget(['cart', 'stripe_payment_intent_id']);

        return redirect()->route('checkout.confirmation');
    }

    // ── Confirmation page ──
    public function confirmation()
    {
        $order = session()->get('order_confirmation');

        if (!$order) {
            return redirect('/');
        }

        return view('checkout-confirmation', compact('order'));
    }
}