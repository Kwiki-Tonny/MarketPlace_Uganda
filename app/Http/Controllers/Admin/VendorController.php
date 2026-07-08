<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Notifications\VendorApproved;

class VendorController extends Controller
{
    /**
     * Get all pending vendors (not approved).
     */
    public function pending()
    {
        $vendors = User::where('role', 'vendor')
                    ->where('is_approved', false)
                    ->get(['id', 'name', 'email', 'phone', 'district', 'created_at', 'suspended_until', 'suspended_reason']); // ✅ added fields

        return response()->json($vendors);
    }

    /**
     * Get all approved vendors.
     */
    public function approved()
    {
        $vendors = User::where('role', 'vendor')
                    ->where('is_approved', true)
                    ->get(['id', 'name', 'email', 'phone', 'district', 'created_at', 'suspended_until', 'suspended_reason']); // ✅ added fields

        return response()->json($vendors);
    }

    /**
     * Approve a vendor.
     */


    public function approve($id)
    {
        $vendor = User::findOrFail($id);
        $vendor->is_approved = true;
        $vendor->save();

        // Send notification
        $vendor->notify(new VendorApproved());

        return response()->json(['message' => 'Vendor approved']);
    }

    /**
     * Reject/delete a vendor.
     */
    public function destroy($id)
    {
        $vendor = User::findOrFail($id);

        if ($vendor->role === 'admin') {
            return response()->json(['error' => 'Cannot delete an admin'], 400);
        }

        $vendor->delete();

        return response()->json(['message' => 'Vendor removed']);
    }

    public function suspend(Request $request, $id)
    {
        $request->validate([
            'suspended_until' => 'required|date|after:now',
            'suspended_reason' => 'nullable|string|max:500',
        ]);

        $vendor = User::findOrFail($id);
        if ($vendor->role !== 'vendor') {
            return response()->json(['error' => 'User is not a vendor'], 400);
        }

        $vendor->suspended_until = $request->suspended_until;
        $vendor->suspended_reason = $request->suspended_reason;
        $vendor->save();

        return response()->json(['message' => 'Vendor suspended successfully.']);
    }

    public function unsuspend($id)
    {
        $vendor = User::findOrFail($id);
        $vendor->suspended_until = null;
        $vendor->suspended_reason = null;
        $vendor->save();

        return response()->json(['message' => 'Vendor unsuspended.']);
    }
}