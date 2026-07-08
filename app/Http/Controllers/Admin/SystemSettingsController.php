<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $general = Setting::where('group', 'general')->get()->pluck('value', 'key')->toArray();
        $payment = Setting::where('group', 'payment')->first();
        $paymentMethods = $payment ? json_decode($payment->value, true) : [];

        return Inertia::render('Admin/SystemSettings', [
            'general' => $general,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'required|string|max:20',
            'site_address' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'general');
        }

        return redirect()->back()->with('success', 'General settings updated.');
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'paymentMethods' => 'required|array',
            'paymentMethods.*.name' => 'required|string|max:255',
            'paymentMethods.*.number' => 'required|string|max:20',
            'paymentMethods.*.instructions' => 'nullable|string',
        ]);

        Setting::set('payment_methods', json_encode($validated['paymentMethods']), 'payment');

        return redirect()->back()->with('success', 'Payment settings updated.');
    }
}