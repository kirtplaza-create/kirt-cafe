<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => (float) $product->price,
                'image' => $product->image,
                'options' => is_string($product->options) ? json_decode($product->options) : $product->options,
            ];
        });

        return response()->json($products);
    }
}