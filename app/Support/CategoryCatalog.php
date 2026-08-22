<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Single source of truth for product categories.
 * Reads admin-managed site_categories/site_subcategories; falls back to the
 * legacy hardcoded list if the tables are missing (e.g. server DB not yet
 * migrated — deploys are FTP-only) so the storefront never breaks.
 */
class CategoryCatalog
{
    const CACHE_KEY = 'site_category_catalog_v1';
    const STOCKED_KEY = 'site_category_stocked_v1';

    /** All ACTIVE categories, each with ->subcategories (array of names), sorted. */
    public static function active()
    {
        return Cache::remember(self::CACHE_KEY, 600, function () {
            try {
                $cats = DB::table('site_categories')
                    ->where('status', 'active')
                    ->orderBy('sort_order')->orderBy('name')
                    ->get();

                if ($cats->isEmpty()) {
                    return collect(self::fallback());
                }

                $subs = DB::table('site_subcategories')
                    ->orderBy('sort_order')->orderBy('name')
                    ->get()
                    ->groupBy('category_id');

                return $cats->map(function ($c) use ($subs) {
                    $c->subcategories = ($subs[$c->id] ?? collect())->pluck('name')->values()->all();
                    return $c;
                });
            } catch (\Throwable $e) {
                Log::warning('CategoryCatalog fallback used: ' . $e->getMessage());
                return collect(self::fallback());
            }
        });
    }

    /** Active categories flagged for the home page grid. */
    public static function forHome()
    {
        return self::active()->filter(fn ($c) => !empty($c->show_on_home))->values();
    }

    /** Active categories flagged for the cosmetics page. */
    public static function forCosmetics()
    {
        return self::active()->filter(fn ($c) => !empty($c->show_on_cosmetics))->values();
    }

    /**
     * Category names that actually have at least one approved product.
     * Used to skip rendering an empty product rail (each rail costs the
     * visitor an XHR, so an always-empty section is pure waste).
     * Cached separately from the catalog itself since it changes as vendors
     * add or get products approved.
     */
    public static function namesWithProducts()
    {
        return Cache::remember(self::STOCKED_KEY, 600, function () {
            try {
                return DB::table('vendor_products')
                    ->where('position', 'approved')
                    ->distinct()
                    ->pluck('category');
            } catch (\Throwable $e) {
                Log::warning('CategoryCatalog::namesWithProducts fallback: ' . $e->getMessage());
                return collect();
            }
        });
    }

    /** forHome()/forCosmetics(), minus categories that would render empty. */
    public static function forHomeStocked()
    {
        $stocked = self::namesWithProducts();
        return self::forHome()->filter(fn ($c) => $stocked->contains($c->name))->values();
    }

    public static function forCosmeticsStocked()
    {
        $stocked = self::namesWithProducts();
        return self::forCosmetics()->filter(fn ($c) => $stocked->contains($c->name))->values();
    }

    /** ['Category Name' => ['Sub A', 'Sub B'], ...] for selects/JS. */
    public static function map(): array
    {
        return self::active()->mapWithKeys(fn ($c) => [$c->name => $c->subcategories])->all();
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::STOCKED_KEY);
    }

    /** Legacy hardcoded catalog as stdClass rows (mirrors the migration seed). */
    private static function fallback(): array
    {
        $data = [
            ['Auto Parts & Accessories', '🚗', true, false, ['Engine Parts', 'Body Parts', 'Suspension & Steering', 'Brakes & Brake Parts', 'Car Electronics', 'Interior Accessories', 'Exterior Accessories', 'Tyres & Wheels', 'Car Cleaning']],
            ['Car Tools & Maintenance', '🛠️', true, false, ['Mechanical Tools', 'Battery Chargers', 'Car Jacks', 'Air Compressors', 'Diagnostic Tools']],
            ['Perfumes & Fragrances', '🧴', true, true, ['Men Perfumes', 'Women Perfumes', 'Body Mists', 'Fragrance Oils', 'Gift Sets']],
            ['Fitness & Gym Equipment', '🏋️', true, false, ['Dumbbells & Weights', 'Barbells & Weight Plates', 'Kettlebells', 'Weight Benches', 'Power Racks & Squat Racks', 'Treadmills', 'Exercise Bikes', 'Cross Trainers / Ellipticals', 'Rowing Machines', 'Steppers', 'Multi Gym Machines', 'Smith Machines', 'Cable Machines', 'Resistance Bands', 'Battle Ropes', 'Medicine Balls', 'Slam Balls', 'Pull-Up Bars', 'Push-Up Bars', 'Ab Rollers', 'Gym Rings', 'Yoga Mats', 'Yoga Blocks', 'Yoga Straps', 'Foam Rollers', 'Punching Bags', 'Boxing Gloves', 'Hand Wraps', 'Skipping Ropes', 'Gym Gloves', 'Weightlifting Belts', 'Wrist / Knee / Elbow Supports', 'Lifting Straps']],
            ['Supplements', '💊', true, false, ['Protein Supplements', 'Mass Gainers', 'Creatine', 'Pre-Workout Supplements', 'Vitamins & Minerals']],
            ['Gym Accessories', '🎽', true, false, ['Water Bottles', 'Shakers', 'Gym Bags', 'Gym Towels']],
            ["Women's Fashion", '👜', true, false, ['Handbags', 'Clutches & Wallets', 'Shoulder Bags', 'Crossbody Bags', 'Women Jewelry', 'Scarves & Shawls', 'Hair Accessories']],
            ["Men's Accessories", '👔', true, false, ['Watches', 'Bracelets', 'Chains', 'Rings', 'Sunglasses', 'Wallets']],
            ['Clothing & Apparel', '👕', true, false, ['Men Clothing', 'Women Clothing', 'Kids Clothing', 'Footwear']],
            // Own top-level fashion category (not a subcategory of Clothing & Apparel) — see storeProduct()'s
            // Men's-Fashion-only attribute handling and resources/views/vendor/new_product.blade.php.
            // TODO: pattern for remaining 4 fashion categories (Women's Fashion clothing, Kids & Baby, Footwear, Fashion Accessories & Bags)
            ["Men's Fashion", '👔', true, false, ['Shirt', 'T-Shirt', 'Jeans', 'Pants', 'Shalwar Kameez', 'Kurta', 'Suit', 'Jacket', 'Coat', 'Hoodie', 'Sweater', 'Shorts', 'Underwear', 'Nightwear']],
            ['Mobile Accessories', '📱', true, false, ['Mobile Covers', 'Chargers', 'Handsfree & Earphones', 'Power Banks', 'Screen Protectors']],
            ['Home & Living', '🏠', true, false, ['Decoration Items', 'LED Lights', 'Clocks', 'Wall Frames', 'Artificial Flowers']],
            ['Gifts & General Items', '🎁', true, false, ['Keychains', 'Mugs', 'Gift Boxes', 'Custom Printed Items', 'Souvenirs']],
            ['Cosmetics', '💄', true, true, ['Skincare', 'Makeup', 'Hair Care', 'Nail Care', 'Body Care', 'Fragrances', 'Beauty Tools', "Men's Grooming", 'Whitening', 'Lotion']],
        ];

        return array_map(function ($row, $i) {
            [$name, $emoji, $home, $cosmetics, $subs] = $row;
            return (object) [
                'id' => 0 - ($i + 1), // synthetic negative id — fallback rows are not editable
                'name' => $name,
                'emoji' => $emoji,
                'status' => 'active',
                'show_on_home' => $home,
                'show_on_cosmetics' => $cosmetics,
                'sort_order' => $i,
                'source' => 'core',
                'subcategories' => $subs,
            ];
        }, $data, array_keys($data));
    }
}
