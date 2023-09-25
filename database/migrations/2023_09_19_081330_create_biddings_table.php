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
        Schema::create('biddings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_event');
            $table->string('email');
            $table->unsignedBigInteger('event_lelang_id')->nullable();
            $table->foreign('event_lelang_id')->references('id')->on('event_lelangs');
            $table->unsignedBigInteger('peserta_npl_id')->nullable();
            $table->foreign('peserta_npl_id')->references('id')->on('peserta_npls');
            $table->unsignedBigInteger('lot_id')->nullable();
            $table->foreign('lot_id')->references('id')->on('lots');
            $table->unsignedBigInteger('npl_id')->nullable();
            $table->foreign('npl_id')->references('id')->on('npls');
            $table->bigInteger('harga_bidding');
            $table->dateTime('waktu')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biddings');
    }
};
