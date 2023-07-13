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
            $table->unsignedBigInteger('kategoribarang_id')->nullable();
            $table->string('barang');
            $table->string('brand');
            $table->string('warna');
            $table->string('lokasi_barang');
            $table->bigInteger('nomer_rangka')->nullable();
            $table->bigInteger('nomer_mesin')->nullable();
            $table->string('tipe_mobil')->nullable();
            $table->string('transisi_mobil')->nullable();
            $table->string('bahan_bakar')->nullable();
            $table->bigInteger('odometer')->nullable();
            $table->string('grade_utama')->nullable();
            $table->string('grade_mesin')->nullable();
            $table->string('grade_interior')->nullable();
            $table->string('grade_exterior')->nullable();
            $table->bigInteger('no_polisi')->nullable();
            $table->string('stnk')->nullable();
            $table->date('stnk_berlaku')->nullable();
            $table->bigInteger('tahun_produksi')->nullable();
            $table->string('bpkb')->nullable();
            $table->string('faktur')->nullable();
            $table->string('sph')->nullable();
            $table->string('kir')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('kategoribarang_id')->references('id')->on('kategori_barangs');

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
