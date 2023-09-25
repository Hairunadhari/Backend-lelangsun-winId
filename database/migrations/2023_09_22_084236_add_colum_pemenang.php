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
        Schema::table('pemenangs', function (Blueprint $table) {
            $table->string('no_rek')->nullable();
            $table->string('nama_pemilik')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->dateTime('tgl_transfer')->nullable();
            $table->string('bukti')->nullable();
            $table->string('tipe_pelunasan')->nullable();
            $table->string('status_pembayaran')->default('Belum Bayar');
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemenangs', function (Blueprint $table) {
            //
        });
    }
};
