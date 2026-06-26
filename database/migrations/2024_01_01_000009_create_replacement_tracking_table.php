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
        Schema::create('replacement_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('replacement_id');
            $table->string('step');
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('replacement_id')->references('id')->on('replacement_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replacement_tracking');
    }
};
