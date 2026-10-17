<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Americano', 'category' => 'Coffee', 'price' => 120.00, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400', 'options' => json_encode(['Cold', 'Hot'])],
            ['name' => 'Tuna Mayo', 'category' => 'Sandwich', 'price' => 180.00, 'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400', 'options' => json_encode(['Small', 'Big'])],
            ['name' => 'Matcha', 'category' => 'Non Coffee', 'price' => 150.00, 'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=400', 'options' => json_encode(['Cold', 'Hot'])],
            ['name' => 'Fried Meatball', 'category' => 'Snack', 'price' => 95.00, 'image' => 'https://images.unsplash.com/photo-1529042410759-befb1204b468?w=400', 'options' => json_encode(['Small', 'Big'])],
            ['name' => 'Lemon Tea', 'category' => 'Tea', 'price' => 110.00, 'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'options' => json_encode(['Only Cold'])],
            ['name' => 'Happy Soda', 'category' => 'Non Coffee', 'price' => 130.00, 'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=400', 'options' => json_encode(['Only Cold'])],
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }
}