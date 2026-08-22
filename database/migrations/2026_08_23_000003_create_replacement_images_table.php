<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Photo evidence for a replacement request — mirrors return_images.
     */
    public function up(): void
    {
        Schema::create('replacement_images', function (Blueprint $table) {
            $table->id();
            // replacement_requests.id is a legacy signed int(11), not Laravel's usual
            // bigint unsigned — match it exactly or the FK constraint fails to form.
            $table->integer('replacement_id');
            $table->string('image_path');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('replacement_id')->references('id')->on('replacement_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_images');
    }
};
