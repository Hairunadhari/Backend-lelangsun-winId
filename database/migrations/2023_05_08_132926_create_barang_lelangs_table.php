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
        Schema::create('barang_lelangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategoribarang_id')->constrained('kategori_barangs')->onDelete('cascade');
            $table->string('barang');
            $table->string('nama_pemilik');
            $table->string('keterangan');
            $table->string('faktur');
            $table->string('ktp');
            $table->string('kwitansi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_lelangs');
    }
};
