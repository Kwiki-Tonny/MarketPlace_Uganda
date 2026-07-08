<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class VendorRankingController extends Controller
{
    public function index()
    {
        // Get all approved vendors with completed orders stats
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
            ->having('orders_count', '>', 0)
            ->get();

        // Map and calculate average order value
        $rankedVendors = $vendors->map(function ($vendor) {
            // Top 5 products per vendor (by quantity)
            $topProducts = Order::where('vendor_id', $vendor->id)
                ->where('status', 'completed')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id')
                ->with('product')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Unknown',
                        'total_quantity' => $item->total_quantity,
                        'total_revenue' => $item->product ? $item->total_quantity * $item->product->price : 0,
                    ];
                });

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
                'top_products' => $topProducts,
            ];
        });

        // Sort by revenue descending and assign rank
        $rankedVendors = $rankedVendors->sortByDesc('total_revenue')->values();
        $rankedVendors = $rankedVendors->map(function ($vendor, $index) {
            $vendor['rank'] = $index + 1;
            return $vendor;
        });

        // Global stats
        $stats = [
            'total_vendors' => $vendors->count(),
            'total_revenue' => $rankedVendors->sum('total_revenue'),
            'total_orders' => $rankedVendors->sum('total_orders'),
        ];

        return Inertia::render('Admin/VendorRankings', [
            'rankedVendors' => $rankedVendors,
            'stats' => $stats,
        ]);
    }
}