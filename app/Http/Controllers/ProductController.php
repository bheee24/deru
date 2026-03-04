<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $related = $product->related(4);

        return view('product', compact('product', 'related'));
    }
}