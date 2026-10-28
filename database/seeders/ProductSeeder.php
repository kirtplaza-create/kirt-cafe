<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing products to prevent duplicates or stale records
        Product::truncate();

        $items = [
            [
                'name' => 'Americano', 
                'category' => 'Coffee', 
                'price' => 85.00, 
                'image' => '/images/americano.png', 
                'options' => ['Cold', 'Hot']
            ],
            
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }
}