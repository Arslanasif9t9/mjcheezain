<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seeds 7 new top-level storefront categories — same pattern as
 * 2026_08_23_000002_add_jewellery_accessories_category.php. The real source
 * of truth is the admin-managed site_categories/site_subcategories tables;
 * CategoryCatalog::fallback() is updated separately for consistency.
 *
 * NOTE: the task's category #3 was named "Gym Accessories" but that name
 * already exists in this DB (seeded by 2026_07_16_000002 with a completely
 * different subcategory set: Water Bottles / Shakers / Gym Bags / Gym
 * Towels — carry-gear, not wearable gym gear). site_categories.name is
 * UNIQUE, so it's seeded here as "Personal Gym Accessories" instead to avoid
 * colliding with / silently overwriting the pre-existing category. Flagged
 * in the commit message + final report.
 */
return new class extends Migration
{
    public function up()
    {
        $categories = [
            [
                'name' => 'Fragrance & Scents',
                'emoji' => '🌸',
                'subs' => ['Perfumes', 'Attars', 'Body Mists', 'Deodorants', 'Perfume Oils', 'Gift Sets'],
            ],
            [
                'name' => 'Bags & Luggage',
                'emoji' => '🧳',
                'subs' => ['Handbags', 'Laptop Bags', 'Shoulder Bags', 'Crossbody Bags', 'Tote Bags', 'Clutches', 'Wallets', 'Backpacks', 'Travel Bags'],
            ],
            [
                // See NOTE above — renamed from spec's "Gym Accessories" to avoid
                // colliding with the pre-existing "Gym Accessories" category.
                'name' => 'Personal Gym Accessories',
                'emoji' => '🏋️',
                'subs' => ['Gym Gloves', 'Weight Belts', 'Lifting Straps', 'Wrist Wraps', 'Knee Sleeves', 'Resistance Bands', 'Skipping Ropes', 'Yoga Mats', 'Gym Bag'],
            ],
            [
                'name' => 'Kitchen & Dining',
                'emoji' => '🍽️',
                'subs' => ['Cooking Essentials', 'Baking Essentials', 'Dining Essentials', 'Drinkware', 'Food Storage', 'Kitchen Appliances', 'Kitchen Tools & Gadgets', 'Serving & Tableware', 'Kitchen Accessories'],
            ],
            [
                'name' => 'Smart Home & Gadgets',
                'emoji' => '🏠',
                'subs' => ['Home Organization & Storage', 'Cleaning & Hygiene Gadgets', 'Smart & Electrical Gadgets', 'Kitchen Utility Gadgets', 'Home Convenience Gadgets'],
            ],
            [
                'name' => 'Personal Care & Daily Essentials',
                'emoji' => '🧴',
                'subs' => ["Men's Essentials", "Women's Essentials", 'Couple Essentials', 'Baby & Kids Essentials', 'Senior Care Essentials', 'Family Essentials'],
            ],
            [
                // Category-only: no dedicated form/JSON column, uses base product
                // fields. A "General" subcategory is seeded because the vendor
                // form's subcategory <select> is a required field.
                'name' => 'Electronic Accessories',
                'emoji' => '🔌',
                'subs' => ['General'],
            ],
        ];

        $nextSort = (int) (DB::table('site_categories')->max('sort_order') + 1);

        foreach ($categories as $i => $cat) {
            if (DB::table('site_categories')->where('name', $cat['name'])->exists()) {
                continue;
            }

            $catId = DB::table('site_categories')->insertGetId([
                'name' => $cat['name'],
                'emoji' => $cat['emoji'],
                'status' => 'active',
                'show_on_home' => true,
                'show_on_cosmetics' => false,
                'sort_order' => $nextSort + $i,
                'source' => 'core',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cat['subs'] as $j => $sub) {
                DB::table('site_subcategories')->insert([
                    'category_id' => $catId,
                    'name' => $sub,
                    'sort_order' => $j,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        $names = [
            'Fragrance & Scents',
            'Bags & Luggage',
            'Personal Gym Accessories',
            'Kitchen & Dining',
            'Smart Home & Gadgets',
            'Personal Care & Daily Essentials',
            'Electronic Accessories',
        ];

        foreach ($names as $name) {
            $cat = DB::table('site_categories')->where('name', $name)->first();
            if ($cat) {
                DB::table('site_subcategories')->where('category_id', $cat->id)->delete();
                DB::table('site_categories')->where('id', $cat->id)->delete();
            }
        }
    }
};
