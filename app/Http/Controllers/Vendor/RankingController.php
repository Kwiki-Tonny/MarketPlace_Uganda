<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RankingController extends Controller
{
    public function index()
    {
        // Get all vendors with completed orders stats
        $vendors = User::where('role', 'vendor')
            ->where('is_approved', true)
            ->withCount(['orders' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['orders as total_revenue' => function ($query) {
                $query->where('status', 'completed');
            }], 'total_price')
            ->withSum(['orders as total_quantity' => function ($query) {
                $query->where('status', 'completed');
            }], 'quantity')
            ->having('orders_count', '>', 0) // only vendors with completed orders
            ->get();

        // Map data and calculate average order value
        $rankedVendors = $vendors->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'email' => $vendor->email,
                'total_orders' => $vendor->orders_count,
                'total_revenue' => (float) $vendor->total_revenue ?? 0,
                'total_quantity' => (int) $vendor->total_quantity ?? 0,
                'avg_order_value' => $vendor->orders_count > 0
                    ? round(($vendor->total_revenue ?? 0) / $vendor->orders_count, 2)
                    : 0,
            ];
        });

        // Sort by revenue descending
        $rankedVendors = $rankedVendors->sortByDesc('total_revenue')->values();

        // Add rank position
        $rankedVendors = $rankedVendors->map(function ($vendor, $index) {
            $vendor['rank'] = $index + 1;
            return $vendor;
        });

        // Find the current vendor's rank and data
        $currentVendorId = Auth::id();
        $myRank = $rankedVendors->firstWhere('id', $currentVendorId);

        // Top 10 vendors
        $topVendors = $rankedVendors->take(10);

        return Inertia::render('Vendor/Rankings', [
            'myRank' => $myRank,
            'topVendors' => $topVendors,
            'totalVendors' => $rankedVendors->count(),
        ]);
    }
}