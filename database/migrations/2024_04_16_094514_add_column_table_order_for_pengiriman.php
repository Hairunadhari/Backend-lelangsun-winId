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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('toko_provinsi_id');
            $table->dropColumn('toko_city_id');
            $table->string('kota_toko')->nullable();
            $table->string('kecamatan_toko')->nullable();
            $table->string('provinsi_toko')->nullable();
            $table->string('kota_user')->nullable();
            $table->string('kecamatan_user')->nullable();
            $table->string('provinsi_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
