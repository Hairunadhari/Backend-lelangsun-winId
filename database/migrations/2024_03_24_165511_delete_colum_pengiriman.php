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
        Schema::table('pengirimen', function (Blueprint $table) {
            $table->dropForeign('pengirimen_order_id_foreign');
            $table->dropColumn('order_id');
            $table->dropColumn('pengiriman');
            $table->dropColumn('lokasi_pengiriman');
            $table->dropColumn('nama_pengirim');
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengirimen', function (Blueprint $table) {
            //
        });
    }
};
