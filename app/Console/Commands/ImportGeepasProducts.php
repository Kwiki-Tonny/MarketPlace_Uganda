<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ImportGeepasProducts extends Command
{
    protected $signature = 'import:geepas {file?}';
    protected $description = 'Import Geepas products from a CSV file';

    public function handle()
    {
        $filePath = $this->argument('file') ?? 'geepas_products.csv';
        $fullPath = storage_path("app/$filePath");

        if (!file_exists($fullPath)) {
            $this->error("File not found: $fullPath");
            $this->info("Place the CSV file in storage/app/$filePath");
            return 1;
        }

        $this->info("Importing from: $fullPath");

        // Read CSV
        $csv = array_map('str_getcsv', file($fullPath));
        $header = array_shift($csv);

        // Auto-detect columns
        $nameIndex = array_search('name', $header);
        $codeIndex = array_search('code', $header);
        $priceIndex = array_search('price', $header);
        $categoryIndex = array_search('category', $header);

        // Fallback if headers don't match exactly (check case-insensitive)
        if ($nameIndex === false) {
            foreach ($header as $idx => $col) {
                if (strtolower(trim($col)) === 'name') $nameIndex = $idx;
                if (strtolower(trim($col)) === 'code') $codeIndex = $idx;
                if (strtolower(trim($col)) === 'price') $priceIndex = $idx;
                if (strtolower(trim($col)) === 'category') $categoryIndex = $idx;
            }
        }

        if ($nameIndex === false || $priceIndex === false) {
            $this->error('CSV must have "name" and "price" columns.');
            return 1;
        }

        // Get or create default category
        $defaultCategory = Category::firstOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics', 'description' => 'All electronic items']
        );

        $count = 0;
        foreach ($csv as $row) {
            // Skip empty rows
            if (empty($row[$nameIndex])) continue;

            $name = trim($row[$nameIndex]);
            $code = isset($row[$codeIndex]) ? trim($row[$codeIndex]) : null;
            $price = floatval($row[$priceIndex]);
            $catName = isset($row[$categoryIndex]) ? trim($row[$categoryIndex]) : 'Electronics';

            // Find or create category
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName]
            );

            Product::updateOrCreate(
                ['code' => $code, 'name' => $name],
                [
                    'description' => $name . ' – Geepas quality product',
                    'price' => $price,
                    'category_id' => $category->id,
                    'images' => json_encode(['/placeholder.jpg']),
                    'is_active' => true,
                ]
            );

            $count++;
        }

        $this->info("✅ Imported $count products successfully.");
        return 0;
    }
}