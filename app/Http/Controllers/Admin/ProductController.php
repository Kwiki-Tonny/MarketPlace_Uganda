<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15); 

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:2048', // if using file uploads
            'is_active' => 'boolean',
        ]);

        // Handle image uploads – store in public/storage/products
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $imagePaths[] = asset('storage/' . $path);
            }
        }

        $product = Product::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'images' => $imagePaths,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'is_active' => 'boolean',
        ]);

        // Handle new images – if any, replace the old ones
        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                $newImages[] = asset('storage/' . $path);
            }
            $product->images = $newImages;
        }

        $product->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }
}