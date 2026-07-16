<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Admin-manageable product categories. Storefront + vendor form read
        // ACTIVE rows; placement flags control the home/cosmetics grids.
        Schema::create('site_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('emoji', 16)->nullable();
            $table->string('status')->default('active'); // active | inactive
            $table->boolean('show_on_home')->default(false);
            $table->boolean('show_on_cosmetics')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('source')->default('core'); // core | admin | vendor
            $table->timestamps();
        });

        Schema::create('site_subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'name']);
            $table->index('category_id');
        });

        // Seed with the previously hardcoded catalog (vendor new_product form)
        $catalog = [
            'Auto Parts & Accessories' => ['🚗', ['Engine Parts', 'Body Parts', 'Suspension & Steering', 'Brakes & Brake Parts', 'Car Electronics', 'Interior Accessories', 'Exterior Accessories', 'Tyres & Wheels', 'Car Cleaning']],
            'Car Tools & Maintenance' => ['🛠️', ['Mechanical Tools', 'Battery Chargers', 'Car Jacks', 'Air Compressors', 'Diagnostic Tools']],
            'Perfumes & Fragrances' => ['🧴', ['Men Perfumes', 'Women Perfumes', 'Body Mists', 'Fragrance Oils', 'Gift Sets']],
            'Fitness & Gym Equipment' => ['🏋️', ['Dumbbells & Weights', 'Barbells & Weight Plates', 'Kettlebells', 'Weight Benches', 'Power Racks & Squat Racks', 'Treadmills', 'Exercise Bikes', 'Cross Trainers / Ellipticals', 'Rowing Machines', 'Steppers', 'Multi Gym Machines', 'Smith Machines', 'Cable Machines', 'Resistance Bands', 'Battle Ropes', 'Medicine Balls', 'Slam Balls', 'Pull-Up Bars', 'Push-Up Bars', 'Ab Rollers', 'Gym Rings', 'Yoga Mats', 'Yoga Blocks', 'Yoga Straps', 'Foam Rollers', 'Punching Bags', 'Boxing Gloves', 'Hand Wraps', 'Skipping Ropes', 'Gym Gloves', 'Weightlifting Belts', 'Wrist / Knee / Elbow Supports', 'Lifting Straps']],
            'Supplements' => ['💊', ['Protein Supplements', 'Mass Gainers', 'Creatine', 'Pre-Workout Supplements', 'Vitamins & Minerals']],
            'Gym Accessories' => ['🎽', ['Water Bottles', 'Shakers', 'Gym Bags', 'Gym Towels']],
            "Women's Fashion" => ['👜', ['Handbags', 'Clutches & Wallets', 'Shoulder Bags', 'Crossbody Bags', 'Women Jewelry', 'Scarves & Shawls', 'Hair Accessories']],
            "Men's Accessories" => ['👔', ['Watches', 'Bracelets', 'Chains', 'Rings', 'Sunglasses', 'Wallets']],
            'Clothing & Apparel' => ['👕', ['Men Clothing', 'Women Clothing', 'Kids Clothing', 'Footwear']],
            'Mobile Accessories' => ['📱', ['Mobile Covers', 'Chargers', 'Handsfree & Earphones', 'Power Banks', 'Screen Protectors']],
            'Home & Living' => ['🏠', ['Decoration Items', 'LED Lights', 'Clocks', 'Wall Frames', 'Artificial Flowers']],
            'Gifts & General Items' => ['🎁', ['Keychains', 'Mugs', 'Gift Boxes', 'Custom Printed Items', 'Souvenirs']],
            'Cosmetics' => ['💄', ['Skincare', 'Makeup', 'Hair Care', 'Nail Care', 'Body Care', 'Fragrances', 'Beauty Tools', "Men's Grooming", 'Whitening', 'Lotion']],
        ];

        $sort = 0;
        foreach ($catalog as $name => [$emoji, $subs]) {
            $catId = DB::table('site_categories')->insertGetId([
                'name' => $name,
                'emoji' => $emoji,
                'status' => 'active',
                'show_on_home' => true,
                'show_on_cosmetics' => in_array($name, ['Cosmetics', 'Perfumes & Fragrances']),
                'sort_order' => $sort++,
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
    }

    public function down()
    {
        Schema::dropIfExists('site_subcategories');
        Schema::dropIfExists('site_categories');
    }
};
