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
        Schema::create('peserta_npls', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nik')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('npwp')->nullable();
            $table->string('foto_npwp')->nullable();
            $table->string('no_rek')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_npls');
    }
};
