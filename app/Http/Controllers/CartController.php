<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // ── Add item to cart ──
    public function add(Request $request)
    {
        $request->validate([
            'product_id'   => 'required',
            'product_name' => 'required|string',
            'product_price'=> 'required|numeric',
            'product_img'  => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        $id = $request->product_id;

        if (isset($cart[$id])) {
            // Already in cart — increment quantity
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $request->product_name,
                'price'    => (float) $request->product_price,
                'img'      => $request->product_img,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success'    => true,
            'message'    => $request->product_name . ' added to bag.',
            'cart_count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    // ── View cart page ──
    public function index()
    {
        $cart  = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return view('cart', compact('cart', 'total'));
    }

    // ── Update quantity ──
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    // ── Remove item ──
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required']);

        $cart = session()->get('cart', []);
        unset($cart[$request->product_id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from bag.');
    }

    // ── Clear entire cart ──
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Your bag has been cleared.');
    }
}