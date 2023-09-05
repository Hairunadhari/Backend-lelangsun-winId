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
        Schema::table('event_lelangs', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_barang_id')->nullable()->after('id');
            $table->string('judul');
            $table->dateTime('waktu');
            $table->text('alamat');
            $table->string('link_lokasi');
            $table->text('deskripsi');
            $table->foreign('kategori_barang_id')->references('id')->on('kategori_barangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_lelangs', function (Blueprint $table) {
            //
        });
    }
};
