<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Update personal info (name + phone) ──
    public function updateInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'nullable|string|max:30',
        ]);

        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->name       = $request->first_name . ' ' . $request->last_name;
        $user->phone      = $request->phone;
        $user->save();

        return redirect(url('/home') . '#profile')
            ->with('success_info', 'Personal details updated successfully.');
    }

    // ── Update default address ──
    public function updateAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:100',
            'postcode'      => 'required|string|max:20',
            'country'       => 'required|string|size:2',
        ]);

        Auth::user()->update($request->only([
            'address_line1', 'address_line2', 'city', 'postcode', 'country',
        ]));

        return redirect(url('/home') . '#profile')
            ->with('success_address', 'Default address saved.');
    }

    // ── Update email (requires current password) ──
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email'            => 'required|email|unique:users,email,' . Auth::id(),
            'current_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password_email' => 'Your current password is incorrect.'])
                ->withFragment('profile');
        }

        Auth::user()->update(['email' => $request->email]);

        return redirect(url('/home') . '#profile')
            ->with('success_email', 'Email address updated.');
    }

    // ── Update password ──
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password_pw' => 'Your current password is incorrect.'])
                ->withFragment('profile');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect(url('/home') . '#profile')
            ->with('success_password', 'Password changed successfully.');
    }
}