<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_basic_info', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('profile_picture', 255)->default('uploads/default_profile.webp');
            $table->string('full_name', 100);
            $table->string('store_name', 100);
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('profile_visibility')->default(true);
            $table->decimal('rating', 3, 2)->nullable();
            $table->boolean('varified')->default(false); // Matching database naming
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_basic_info');
    }
};
