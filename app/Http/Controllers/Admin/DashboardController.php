<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Stats
        $totalVendors = User::where('role', 'vendor')->count();
        $pendingVendors = User::where('role', 'vendor')->where('is_approved', false)->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        // Order stats
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $recentOrders = Order::with(['vendor', 'product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'vendor_name' => $order->vendor->name,
                    'product_name' => $order->product->name,
                    'quantity' => $order->quantity,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('d M Y, h:i A'),
                ];
            });

        // Vendor analytics
        $vendors = User::where('role', 'vendor')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'phone', 'district', 'is_approved', 'created_at'])
            ->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'phone' => $vendor->phone ?? 'N/A',
                    'district' => $vendor->district ?? 'N/A',
                    'status' => $vendor->is_approved ? 'Approved' : 'Pending',
                    'registered_at' => $vendor->created_at->format('d M Y'),
                ];
            });

        // Product analytics – count per category
        $categoryCounts = Category::withCount('products')->get(['id', 'name', 'products_count']);

        // Ranking (placeholder by registration date)
        $ranking = User::where('role', 'vendor')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'is_approved', 'created_at'])
            ->map(function ($vendor, $index) {
                return [
                    'rank' => $index + 1,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'status' => $vendor->is_approved ? 'Approved' : 'Pending',
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalVendors' => $totalVendors,
                'pendingVendors' => $pendingVendors,
                'totalProducts' => $totalProducts,
                'totalCategories' => $totalCategories,
                'totalOrders' => $totalOrders,
                'pendingOrders' => $pendingOrders,
            ],
            'vendors' => $vendors,
            'categoryCounts' => $categoryCounts,
            'ranking' => $ranking,
            'recentOrders' => $recentOrders,
        ]);
    }
}