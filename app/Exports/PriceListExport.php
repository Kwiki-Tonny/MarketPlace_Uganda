<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PriceListExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with('category')
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                return [
                    'code' => $product->code,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'Uncategorized',
                    'price' => number_format($product->price, 0),
                    'image' => $product->images[0] ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return ['Code', 'Name', 'Category', 'Price (UGX)', 'Image URL'];
    }
}