<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vendor-suggested "Other" categories, queued for admin review.
        // Fully isolated: nothing else reads or writes this table.
        Schema::create('category_suggestions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('category_name');
            $table->string('subcategory_name')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('status')->default('pending'); // pending | reviewed | added | rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_suggestions');
    }
};
