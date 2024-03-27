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
            $table->string('type')->nullable();
            $table->dropColumn('etd');
            $table->dropColumn('description_service');
            $table->dropColumn('service');
            $table->dropColumn('toko_detail_alamat');
            $table->dropColumn('toko_city_name');
            $table->dropColumn('toko_provinsi_name');
            $table->dropColumn('detail_alamat');
            $table->dropColumn('city_name');
            $table->dropColumn('province_name');
            $table->dropColumn('email_order');
            $table->dropColumn('notelp');
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
