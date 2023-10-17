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
        Schema::create('banner_lelang_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_lelang_id')->nullable();
            $table->foreign('banner_lelang_id')->references('id')->on('banner_lelangs');
            $table->string('gambar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_lelang_images');
    }
};
