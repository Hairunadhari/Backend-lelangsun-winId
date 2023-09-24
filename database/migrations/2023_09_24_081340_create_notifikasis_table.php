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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_npl_id')->nullable();
            $table->foreign('peserta_npl_id')->references('id')->on('peserta_npls');
            $table->string('type')->nullable();
            $table->string('judul')->nullable();
            $table->text('pesan')->nullable();
            $table->string('is_read')->default('belum dibaca');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
