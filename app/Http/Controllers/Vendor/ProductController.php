<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PriceListExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Display the product catalog (card view with order button).
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->paginate(12); // ✅ 12 per page (grid layout)

        $categories = Category::all();

        return Inertia::render('Vendor/Products', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    /**
     * Display the price list (table view with download buttons).
     */
    public function priceList(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->orderBy('name')->paginate(20); // ✅ paginate

        $categories = Category::all();

        return Inertia::render('Vendor/PriceList', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    /**
     * Download price list as Excel.
     */
    public function download()
    {
        return Excel::download(new PriceListExport, 'price_list_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Download price list as PDF (with images).
     */
    public function downloadPDF()
    {
        $products = Product::with('category')->where('is_active', true)->get();
        $pdf = Pdf::loadView('exports.price-list-pdf', compact('products'));
        return $pdf->download('price_list_' . date('Y-m-d') . '.pdf');
    }
}