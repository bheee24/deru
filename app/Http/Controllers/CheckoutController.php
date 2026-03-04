<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    protected function shippingRate(string $method, float $subtotal): float
    {
        return match($method) {
            'uk_dpd'      => $subtotal >= 80 ? 0 : 5.50,
            'intl_europe' => 12.50,
            'intl_world'  => 12.50,
            default       => 5.50,
        };
    }

    protected function shippingLabel(string $method): string
    {
        return match($method) {
            'uk_dpd'      => 'DPD Delivery (1–2 Days)',
            'intl_europe' => 'European Tracked (4–6 Days)',
            'intl_world'  => 'International Tracked (4–8 Days)',
            default       => $method,
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
        $shipping = $this->shippingRate('uk_dpd', $subtotal);
        $total    = round(($subtotal + $shipping) * 100);

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

    // ── Update PaymentIntent when shipping changes ──
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

    // ── Store order after Stripe confirms payment ──
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

        // ── Verify payment with Stripe ──
        $intent = PaymentIntent::retrieve($request->payment_intent_id, [
            'expand' => ['payment_method'],
        ]);

        if ($intent->status !== 'succeeded') {
            return back()->withErrors(['payment' => 'Payment was not completed. Please try again.']);
        }

        // ── Pull card details from Stripe ──
        $cardLast4 = $intent->payment_method?->card?->last4 ?? null;
        $cardBrand = $intent->payment_method?->card?->brand ?? null;

        $cart     = session()->get('cart', []);
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shipping = $this->shippingRate($request->shipping_method, $subtotal);
        $total    = $subtotal + $shipping;

        // ── Save to database in a transaction ──
        $order = DB::transaction(function () use ($request, $cart, $subtotal, $shipping, $total, $intent, $cardLast4, $cardBrand) {

            $order = Order::create([
                'user_id'           => auth()->id(),
                'reference'         => 'DERU-' . strtoupper(substr($intent->id, -8)),
                'payment_intent_id' => $intent->id,
                'card_last4'        => $cardLast4,
                'card_brand'        => $cardBrand,
                'subtotal'          => $subtotal,
                'shipping_cost'     => $shipping,
                'total'             => $total,
                'shipping_method'   => $this->shippingLabel($request->shipping_method),
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'address_line1'     => $request->address_line1,
                'address_line2'     => $request->address_line2,
                'city'              => $request->city,
                'postcode'          => $request->postcode,
                'country'           => $request->country,
                'status'            => 'pending',
                'notes'             => $request->notes,
            ]);

            // ── Save each item ──
            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => is_numeric($id) ? $id : null,
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                    'product_img'  => $item['img'] ?? null,
                ]);
            }

            return $order;
        });

        // ── Store minimal confirmation data in session ──
        session()->put('order_confirmation', [
            'id'       => $order->id,
            'reference'=> $order->reference,
        ]);

        session()->forget(['cart', 'stripe_payment_intent_id']);

        return redirect()->route('checkout.confirmation');
    }

    // ── Confirmation page ──
    public function confirmation()
    {
        $data = session()->get('order_confirmation');

        if (!$data) {
            return redirect('/');
        }

        // Load fresh from DB with items
        $order = Order::with('items')->findOrFail($data['id']);

        return view('checkout-confirmation', compact('order'));
    }
}