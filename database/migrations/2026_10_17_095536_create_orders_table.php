<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('table_number')->nullable();
            $table->string('order_type')->default('Dine In'); // Dine In, Takeaway
            $table->string('payment_method')->default('Cash'); // Cash, Card, Scan
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->string('status')->default('completed'); // pending, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
