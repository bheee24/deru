<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // public function index(Request $request)
    // {
    //     $query = User::where('role', 'user')
    //         ->withCount('orders')
    //         ->withSum('orders', 'total')
    //         ->latest();

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //               ->orWhere('email', 'like', "%{$search}%");
    //         });
    //     }

    //     $customers = $query->paginate(15)->withQueryString();

    //     return view('admin.customers.index', compact('customers'));
    // }
    // 
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load('orders');
        return view('admin.customers.show', compact('customer'));
    }

    public function toggle(User $customer)
    {
        // Prevent admin from disabling themselves
        if ($customer->id === auth()->id()) {
            return back()->with('error', 'You cannot disable your own account.');
        }

        $customer->update(['is_active' => !$customer->is_active]);

        $status = $customer->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "{$customer->name} has been {$status}.");
    }
}