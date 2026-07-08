<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Vendor/Settings', [
            'user' => Auth::user()->only(['id', 'name', 'email', 'phone', 'district']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'district' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'preferences' => 'required|array',
            'preferences.order_status' => 'boolean',
            'preferences.promotions' => 'boolean',
        ]);

        $user->preferences = $validated['preferences'];
        $user->save();

        return redirect()->back()->with('success', 'Notification preferences updated.');
    }
}