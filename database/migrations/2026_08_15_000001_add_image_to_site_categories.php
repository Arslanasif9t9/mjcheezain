<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets the admin set the tile picture for the home page "Shop by Category"
 * section. Previously those images were a hardcoded map in the Blade file,
 * so a new category could only ever get an emoji circle.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('site_categories') && !Schema::hasColumn('site_categories', 'image')) {
            Schema::table('site_categories', function (Blueprint $table) {
                $table->string('image')->nullable()->after('emoji');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('site_categories') && Schema::hasColumn('site_categories', 'image')) {
            Schema::table('site_categories', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
