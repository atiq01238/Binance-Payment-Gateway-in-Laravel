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
            $table->string('symbol'); // The trading pair symbol, e.g., BTCUSDT
            $table->enum('side', ['BUY', 'SELL']); // The side of the order, either BUY or SELL
            $table->enum('type', ['MARKET', 'LIMIT']); // The type of order, either MARKET or LIMIT
            $table->decimal('quantity', 16, 8); // The quantity of the order, with a precision suitable for cryptocurrencies
            $table->decimal('price', 16, 8)->nullable(); // The price of the order (nullable for market orders)
            $table->string('order_id')->unique(); // The order ID returned by Binance
            $table->timestamps(); // Laravel's created_at and updated_at timestamps

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
