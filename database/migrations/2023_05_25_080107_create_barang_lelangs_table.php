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
            $table->string('brand');
            $table->string('warna');
            $table->string('lokasi_barang');
            $table->bigInteger('nomer_rangka');
            $table->bigInteger('nomer_mesin');
            $table->string('tipe_mobil');
            $table->string('transisi_mobil');
            $table->string('bahan_bakar');
            $table->bigInteger('odometer');
            $table->string('grade_utama');
            $table->string('grade_mesin');
            $table->string('grade_interior');
            $table->string('grade_exterior');
            $table->bigInteger('no_polisi');
            $table->string('stnk');
            $table->date('stnk_berlaku');
            $table->bigInteger('tahun_produksi');
            $table->string('bpkb');
            $table->string('faktur');
            $table->string('sph');
            $table->string('kir');
            $table->text('keterangan');
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
