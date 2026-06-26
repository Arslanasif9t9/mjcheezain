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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('vendor_id');
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_charges', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->unsignedBigInteger('shipping_address_id');
            $table->timestamp('order_date')->useCurrent();
            $table->enum('fulfillment', ['pending', 'unfulfillment', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->boolean('vendor_visible')->default(false);
            $table->enum('payment_method', ['bank_transfer', 'cash_on_delivery', 'credit_card']);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('customer_addresses')->onDelete('cascade');
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
