<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Flexible JSON stores for the 6 new categories' category-specific product
 * fields — same pattern as vendor_products.fashion_attributes /
 * jewelry_attributes (own column per category, different field shape per
 * category, no shared bucket). "Electronic Accessories" (the 7th new
 * category) gets no column — it only uses the base product fields already
 * common to every category.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->json('fragrance_attributes')->nullable()->after('jewelry_attributes');
            $table->json('bags_attributes')->nullable()->after('fragrance_attributes');
            $table->json('gym_attributes')->nullable()->after('bags_attributes');
            $table->json('kitchen_attributes')->nullable()->after('gym_attributes');
            $table->json('smarthome_attributes')->nullable()->after('kitchen_attributes');
            $table->json('personalcare_attributes')->nullable()->after('smarthome_attributes');
        });
    }

    public function down()
    {
        Schema::table('vendor_products', function (Blueprint $table) {
            $table->dropColumn([
                'fragrance_attributes',
                'bags_attributes',
                'gym_attributes',
                'kitchen_attributes',
                'smarthome_attributes',
                'personalcare_attributes',
            ]);
        });
    }
};
