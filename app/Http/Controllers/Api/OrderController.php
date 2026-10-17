<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    // Get active/pending orders
    public function index(): JsonResponse
    {
        $orders = Order::with('items')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    // Save new POS order
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'customer_name' => 'nullable|string',
            'table_number' => 'nullable|string',
            'order_type' => 'required|string',
            'payment_method' => 'required|string',
            'sub_total' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'cart' => 'required|array|min:1',
            'cart.*.name' => 'required|string',
            'cart.*.unitPrice' => 'required|numeric',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $newOrder = Order::create([
                'order_number' => $validated['order_number'],
                'customer_name' => $validated['customer_name'] ?? 'Guest',
                'table_number' => $validated['table_number'] ?? 'N/A',
                'order_type' => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'sub_total' => $validated['sub_total'],
                'tax_amount' => $validated['tax_amount'],
                'grand_total' => $validated['grand_total'],
                'status' => 'pending', // MUST BE 'pending'
            ]);

            foreach ($validated['cart'] as $item) {
                $newOrder->items()->create([
                    'product_name' => $item['name'],
                    'unit_price' => $item['unitPrice'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['unitPrice'] * $item['quantity'],
                ]);
            }

            return $newOrder;
        });

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items')
        ], 201);
    }

    // Update status (complete or cancel)
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }
}