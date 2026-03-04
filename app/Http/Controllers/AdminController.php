<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Product;
use App\Models\Order;



class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalProducts  = \App\Models\Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        // These will be 0 until you build the Orders module
        $totalOrders  = 0;
        $totalRevenue = 0;
        $recentOrders = collect();

        $recentCustomers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        // return view('admin.dashboard', compact(
        //     'totalProducts',
        //     'totalCustomers',
        //     'totalOrders',
        //     'totalRevenue',
        //     'recentOrders',
        //     'recentCustomers'
        // ));
        
        return view('admin.dashboard', [
                   'totalProducts'   => Product::count(),
                   'totalCustomers'  => User::where('role', 'user')->count(),
                   'totalOrders'     => Order::count(),
                   'totalRevenue'    => Order::whereNotIn('status', ['cancelled'])->sum('total'),
                   'recentOrders'    => Order::with('user')->latest()->take(5)->get(),
                   'recentCustomers' => User::where('role', 'user')->latest()->take(6)->get(),
               ]);
    }
}