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
        Schema::create('pemenangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bidding_id')->nullable();
            $table->foreign('bidding_id')->references('id')->on('biddings');
            $table->unsignedBigInteger('npl_id')->nullable();
            $table->foreign('npl_id')->references('id')->on('npls');
            $table->string('no_rek');
            $table->string('nama_pemilik');
            $table->bigInteger('nominal');
            $table->dateTime('tgl_transfer');
            $table->string('bukti');
            $table->string('tipe_pelunasan');
            $table->string('status_pembayaran')->default('Belum Bayar');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemenangs');
    }
};
