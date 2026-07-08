<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorBulkMail;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function create()
    {
        $vendorCount = User::where('role', 'vendor')->where('is_approved', true)->count();
        return Inertia::render('Admin/Notifications', [
            'vendorCount' => $vendorCount,
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|in:all,approved,pending',
        ]);

        // Build recipient query
        $query = User::where('role', 'vendor');
        if ($validated['recipients'] === 'approved') {
            $query->where('is_approved', true);
        } elseif ($validated['recipients'] === 'pending') {
            $query->where('is_approved', false);
        }
        // 'all' includes both approved and pending

        $vendors = $query->get();

        if ($vendors->isEmpty()) {
            return redirect()->back()->with('error', 'No vendors found for selected recipients.');
        }

        // Send email to each vendor
        foreach ($vendors as $vendor) {
            Mail::to($vendor->email)
                ->queue(new VendorBulkMail($validated['subject'], $validated['message']));
        }

        return redirect()->back()->with('success', 'Notification sent to ' . $vendors->count() . ' vendors.');
    }
}