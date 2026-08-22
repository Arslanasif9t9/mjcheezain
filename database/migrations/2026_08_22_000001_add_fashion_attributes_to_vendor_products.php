<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Flexible JSON store for category-specific product fields.
     * Named `fashion_attributes` (not `attributes`) — the base Eloquent Model
     * class already has a protected $attributes property, so a column named
     * "attributes" risks confusing collisions on models that map this table
     * (VendorProduct, Product). Started with Men's Fashion; will hold the
     * same shape for the other 4 planned fashion categories later.
     */
    public function up()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->json('fashion_attributes')->nullable()->after('part_type');
        });
    }

    public function down()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn('fashion_attributes');
        });
    }
};
