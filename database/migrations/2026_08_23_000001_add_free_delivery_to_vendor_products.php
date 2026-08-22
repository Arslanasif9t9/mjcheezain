<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lets a vendor mark a product as free-delivery. When true, this
     * product's line contributes Rs 0 to the flat per-order shipping fee
     * instead of the normal Rs 300 — see CheckoutController/CartController.
     */
    public function up()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->boolean('free_delivery')->default(false)->after('delivery_charges');
        });
    }

    public function down()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn('free_delivery');
        });
    }
};
