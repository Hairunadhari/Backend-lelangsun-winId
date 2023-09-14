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
        Schema::create('lot_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_lelang_id')->nullable();
            $table->foreign('barang_lelang_id')->references('id')->on('barang_lelangs');
            $table->unsignedBigInteger('event_lelang_id')->nullable();
            $table->foreign('event_lelang_id')->references('id')->on('event_lelangs');
            $table->unsignedBigInteger('lot_id')->nullable();
            $table->foreign('lot_id')->references('id')->on('lots');
            $table->dateTime('tanggal');
            $table->string('status_item')->default('active');
            $table->string('status')->default('active');
            $table->bigInteger('harga_awal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_items');
    }
};
