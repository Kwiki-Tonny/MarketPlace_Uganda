<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Create a default category
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            ['name' => 'Electronics', 'description' => 'All electronic items']
        );

        // Sample products (we'll expand to 222 later)
        $products = [
            ['code' => 'GSB 5485', 'name' => '1.8L/600W/Icecrsh', 'price' => 76000],
            ['code' => 'GSB 44027 T', 'name' => '1.8L/600W/Unbk/ice', 'price' => 78000],
            ['code' => 'GSB 44030', 'name' => '2in1/1.5/Unbk/550W', 'price' => 84000],
            ['code' => 'GSB 44107', 'name' => '1.6L/650W', 'price' => 82000],
            ['code' => 'GSB 6104', 'name' => '3in1/1.5/Unbk/400W', 'price' => 83000],
        ];

        foreach ($products as $item) {
            Product::create([
                'code' => $item['code'],
                'name' => $item['name'],
                'description' => $item['name'] . ' – Geepas quality product',
                'price' => $item['price'],
                'category_id' => $electronics->id,
                'images' => json_encode(['/placeholder.jpg']),
                'is_active' => true,
            ]);
        }
    }
}