<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tracks the pre-existing `japan_products` table (previously created out-of-band,
 * no migration on record). Guarded so it's a safe no-op on any DB that already
 * has the table (e.g. local), and creates it fresh — matching the real live
 * schema exactly (confirmed via DESCRIBE) — on DBs that don't (production).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('japan_products')) {
            return;
        }

        Schema::create('japan_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->string('product_name');
            $table->integer('category_id')->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('made_in', 100)->nullable();
            $table->enum('conditionp', ['New', 'Used', 'Refurbished'])->nullable()->default('New');
            $table->decimal('selling_price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->integer('quantity')->nullable()->default(1);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->default('approved');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('japan_products');
    }
};
