<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds "Men's Fashion" as its own top-level storefront category (not a
 * subcategory of "Clothing & Apparel") — the real source of truth is the
 * admin-managed site_categories/site_subcategories tables (see
 * 2026_07_16_000002_create_site_categories_tables.php), not the hardcoded
 * array in CategoryCatalog::fallback() (that array is only a DB-missing
 * fallback and is updated separately for consistency).
 *
 * TODO: same pattern for Women's Fashion / Kids & Baby / Footwear / Bags & Accessories
 * — insert their site_categories row + site_subcategories rows here (or a
 * follow-up migration) when those categories are built.
 */
return new class extends Migration
{
    public function up()
    {
        if (DB::table('site_categories')->where('name', "Men's Fashion")->exists()) {
            return;
        }

        $catId = DB::table('site_categories')->insertGetId([
            'name' => "Men's Fashion",
            'emoji' => '👔',
            'status' => 'active',
            'show_on_home' => true,
            'show_on_cosmetics' => false,
            'sort_order' => (int) (DB::table('site_categories')->max('sort_order') + 1),
            'source' => 'core',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subs = [
            'Shirt', 'T-Shirt', 'Jeans', 'Pants', 'Shalwar Kameez', 'Kurta',
            'Suit', 'Jacket', 'Coat', 'Hoodie', 'Sweater', 'Shorts',
            'Underwear', 'Nightwear',
        ];

        foreach ($subs as $i => $sub) {
            DB::table('site_subcategories')->insert([
                'category_id' => $catId,
                'name' => $sub,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        $cat = DB::table('site_categories')->where('name', "Men's Fashion")->first();
        if ($cat) {
            DB::table('site_subcategories')->where('category_id', $cat->id)->delete();
            DB::table('site_categories')->where('id', $cat->id)->delete();
        }
    }
};
