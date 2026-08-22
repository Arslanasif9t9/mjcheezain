<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds "Jewellery & Accessories" as its own top-level storefront category —
 * same pattern as 2026_08_22_000002_add_mens_fashion_category.php. The real
 * source of truth is the admin-managed site_categories/site_subcategories
 * tables; CategoryCatalog::fallback() is updated separately for consistency.
 */
return new class extends Migration
{
    public function up()
    {
        if (DB::table('site_categories')->where('name', 'Jewellery & Accessories')->exists()) {
            return;
        }

        $catId = DB::table('site_categories')->insertGetId([
            'name' => 'Jewellery & Accessories',
            'emoji' => '💍',
            'status' => 'active',
            'show_on_home' => true,
            'show_on_cosmetics' => false,
            'sort_order' => (int) (DB::table('site_categories')->max('sort_order') + 1),
            'source' => 'core',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subs = [
            'Rings', 'Necklace', 'Earrings', 'Bangles', 'Chain', 'Pendants',
            'Anklets', 'Nose Pins', 'Brooches', 'Charms', 'Jewelry Sets',
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
        $cat = DB::table('site_categories')->where('name', 'Jewellery & Accessories')->first();
        if ($cat) {
            DB::table('site_subcategories')->where('category_id', $cat->id)->delete();
            DB::table('site_categories')->where('id', $cat->id)->delete();
        }
    }
};
