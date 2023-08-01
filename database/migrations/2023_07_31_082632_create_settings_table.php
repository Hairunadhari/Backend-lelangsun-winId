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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('deskripsi');
            $table->bigInteger('no_telp');
            $table->bigInteger('wa');
            $table->string('email');
            $table->text('alamat');
            $table->string('ig');
            $table->string('fb');
            $table->string('twitter');
            $table->string('yt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
