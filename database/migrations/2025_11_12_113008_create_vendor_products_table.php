<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('category');
            $table->string('subcategory');
            $table->integer('quantity');
            $table->string('brand');
            $table->string('model')->nullable();
            $table->string('condition');
            $table->decimal('original_price', 10, 2);
            $table->decimal('delivery_charges', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->string('shipping_method');
            $table->string('shipping_time');
            $table->text('description');
            $table->string('location');
            $table->string('made_in')->nullable();
            $table->string('return_policy')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('position')->default(0);
            $table->integer('views')->default(0);
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_products');
    }
};