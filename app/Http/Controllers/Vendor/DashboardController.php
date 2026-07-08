<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        // ----- Basic stats -----
        $totalOrders = Order::where('vendor_id', $user->id)->count();
        $pendingOrders = Order::where('vendor_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Monthly sales (completed orders in current month)
        $monthlySales = Order::where('vendor_id', $user->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // ----- Revenue for all vendors (completed orders) -----
        $vendorsRevenue = User::where('role', 'vendor')
            ->where('is_approved', true)
            ->withSum(['orders as revenue' => function ($q) {
                $q->where('status', 'completed');
            }], 'total_price')
            ->having('revenue', '>', 0)
            ->get()
            ->sortByDesc('revenue')
            ->values();

        // Find current vendor's rank (1-based)
        $rank = 1;
        foreach ($vendorsRevenue as $index => $vendor) {
            if ($vendor->id === $user->id) {
                $rank = $index + 1;
                break;
            }
        }

        // If the vendor has no completed orders, rank is null
        if ($rank > $vendorsRevenue->count()) {
            $rank = null; // or 'N/A'
        }

        $vendorData = [
            'name' => $user->name,
            'rank' => $rank,
            'totalOrders' => $totalOrders,
            'monthlySales' => $monthlySales,
            'pendingOrders' => $pendingOrders,
        ];

        return Inertia::render('Vendor/Dashboard', [
            'vendor' => $vendorData,
        ]);
    }
}