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
        Schema::table('pembelian_npls', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_npl_id')->nullable()->after('id');
            $table->unsignedBigInteger('event_lelang_id')->nullable()->after('id');
            $table->string('type_pembelian');
            $table->string('type_transaksi');
            $table->string('verifikasi')->default(0);
            $table->string('bukti');
            $table->string('no_rek');
            $table->string('nama_pemilik');
            $table->bigInteger('nominal');
            $table->dateTime('tgl_transfer');
            $table->string('pesan_verifikasi');
            $table->string('status')->default('active');

            $table->foreign('peserta_npl_id')->references('id')->on('peserta_npls');
            $table->foreign('event_lelang_id')->references('id')->on('event_lelangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_npls', function (Blueprint $table) {
            //
        });
    }
};
