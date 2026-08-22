<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Flexible JSON store for Jewellery & Accessories category-specific product
 * fields — same pattern as vendor_products.fashion_attributes (see
 * 2026_08_22_000001_add_fashion_attributes_to_vendor_products.php), but its
 * own separate column (jewelry_attributes) since jewellery needs a different
 * field shape than fashion and shouldn't share the fashion_attributes bucket.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->json('jewelry_attributes')->nullable()->after('fashion_attributes');
        });
    }

    public function down()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn('jewelry_attributes');
        });
    }
};
