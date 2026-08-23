<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Completes the "Fashion" family of top-level categories started by
 * 2026_08_22_000002_add_mens_fashion_category.php (Men's Fashion). Adds the
 * remaining 4: Women's Fashion (clothing), Kids & Baby Fashion, Footwear,
 * Fashion Accessories & Bags — same pattern: own top-level site_categories
 * row + site_subcategories rows, own field partials, all sharing the
 * vendor_products.fashion_attributes JSON column (branched by category in
 * VendorController::buildFashionAttributes()).
 *
 * IMPORTANT: a "Women's Fashion" category (id 7, emoji originally 👜) already
 * existed pre-migration, seeded 2026-07-16 as a bags/accessories-for-women
 * category (Handbags, Clutches & Wallets, Shoulder Bags, Crossbody Bags,
 * Women Jewelry, Scarves & Shawls, Hair Accessories) — unrelated to the
 * women's CLOTHING category this task asked for, and never wired to any
 * fields/JSON column of its own (category-only, plain product fields). It
 * had zero vendor_products rows using it at migration time (verified via
 * tinker), so rather than create a second, confusingly-identical-looking
 * "Women's Fashion" row, this migration REPURPOSES that existing row: its
 * subcategories are replaced with the women's-clothing type list and it
 * becomes the 2nd "own top-level category, own JSON bucket" fashion
 * category (same shape as Men's Fashion). down() restores the original
 * bags/accessories subcategory list if this migration is rolled back.
 */
return new class extends Migration
{
    private array $originalWomensFashionSubs = [
        'Handbags', 'Clutches & Wallets', 'Shoulder Bags', 'Crossbody Bags',
        'Women Jewelry', 'Scarves & Shawls', 'Hair Accessories',
    ];

    private array $womensClothingSubs = [
        'Dress', 'Kurti', 'Saree', 'Shalwar Kameez', 'Abaya', 'Hijab', 'Top',
        'Shirt', 'Trousers', 'Skirt', 'Lehenga', 'Gown', 'Jacket', 'Nightwear',
    ];

    private array $kidsFashionSubs = [
        'Baby Dress', 'Boys Clothing', 'Girls Clothing', 'T-Shirt', 'Pants',
        'Frock', 'Kurta', 'Shalwar Kameez', 'School Wear', 'Jacket',
        'Sweater', 'Nightwear',
    ];

    private array $footwearSubs = [
        'Sneakers', 'Formal Shoes', 'Boots', 'Sandals', 'Slippers', 'Heels',
        'Flats', 'Sports Shoes', 'School Shoes', 'Loafers', 'Khussa',
    ];

    private array $fashionAccessoriesSubs = [
        // Bags group
        'Handbag', 'Shoulder Bag', 'Crossbody', 'Tote', 'Clutch', 'Backpack',
        'Wallet', 'Travel Bag', 'Laptop Bag',
        // Accessories group
        'Belt', 'Cap', 'Hat', 'Scarf', 'Gloves', 'Tie', 'Sunglasses',
        'Fashion Jewelry', 'Hair Accessories', 'Other Fashion Accessories',
    ];

    public function up()
    {
        // 1) Repurpose the existing "Women's Fashion" (id assumed by name
        // lookup, not hardcoded id) into the women's-clothing fashion category.
        $womens = DB::table('site_categories')->where('name', "Women's Fashion")->first();
        if ($womens) {
            DB::table('site_categories')->where('id', $womens->id)->update([
                'emoji' => '👗',
                'updated_at' => now(),
            ]);
            DB::table('site_subcategories')->where('category_id', $womens->id)->delete();
            foreach ($this->womensClothingSubs as $i => $sub) {
                DB::table('site_subcategories')->insert([
                    'category_id' => $womens->id,
                    'name' => $sub,
                    'sort_order' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // Defensive fallback: row missing entirely (fresh DB) — create it
            // fresh, same as the other 3 new categories below.
            $this->insertCategoryIfMissing("Women's Fashion", '👗', $this->womensClothingSubs);
        }

        // 2) The 3 brand-new categories.
        $this->insertCategoryIfMissing('Kids & Baby Fashion', '🧸', $this->kidsFashionSubs);
        $this->insertCategoryIfMissing('Footwear', '👟', $this->footwearSubs);
        $this->insertCategoryIfMissing('Fashion Accessories & Bags', '👜', $this->fashionAccessoriesSubs);
    }

    private function insertCategoryIfMissing(string $name, string $emoji, array $subs): void
    {
        if (DB::table('site_categories')->where('name', $name)->exists()) {
            return;
        }

        $catId = DB::table('site_categories')->insertGetId([
            'name' => $name,
            'emoji' => $emoji,
            'status' => 'active',
            'show_on_home' => true,
            'show_on_cosmetics' => false,
            'sort_order' => (int) (DB::table('site_categories')->max('sort_order') + 1),
            'source' => 'core',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
        foreach (['Kids & Baby Fashion', 'Footwear', 'Fashion Accessories & Bags'] as $name) {
            $cat = DB::table('site_categories')->where('name', $name)->first();
            if ($cat) {
                DB::table('site_subcategories')->where('category_id', $cat->id)->delete();
                DB::table('site_categories')->where('id', $cat->id)->delete();
            }
        }

        $womens = DB::table('site_categories')->where('name', "Women's Fashion")->first();
        if ($womens) {
            DB::table('site_categories')->where('id', $womens->id)->update([
                'emoji' => '👜',
                'updated_at' => now(),
            ]);
            DB::table('site_subcategories')->where('category_id', $womens->id)->delete();
            foreach ($this->originalWomensFashionSubs as $i => $sub) {
                DB::table('site_subcategories')->insert([
                    'category_id' => $womens->id,
                    'name' => $sub,
                    'sort_order' => $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
