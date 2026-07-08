<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($request->product_id);
            $total = $product->price * $request->quantity;

            $order = Order::create([
                'vendor_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $total,
                'status' => 'pending',
            ]);

            // Log success (optional)
            Log::info('Order placed', ['order_id' => $order->id, 'vendor_id' => Auth::id()]);

            // ✅ Redirect to the vendor orders page
            return redirect()->route('vendor.orders')->with('success', 'Order placed!');

        } catch (\Exception $e) {
            Log::error('Order placement failed', ['error' => $e->getMessage()]);
            return back()->withErrors('Order could not be placed.');
        }
    }

    public function index()
    {
        $orders = Order::with('product')
            ->where('vendor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Debug: log the count to ensure data is present
        Log::info('Vendor orders count', ['vendor_id' => Auth::id(), 'count' => $orders->count()]);

        return Inertia::render('Vendor/Orders', [
            'orders' => $orders,
        ]);
    }

    public function lastUpdated()
    {
        $lastUpdated = Order::where('vendor_id', Auth::id())->max('updated_at');

        return response()->json([
            'last_updated' => $lastUpdated ? $lastUpdated->toISOString() : null,
        ]);
    }
}