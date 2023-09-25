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
            $table->dropColumn('no_rek');
            $table->dropColumn('nama_pemilik');
            $table->dropColumn('nominal');
            $table->dropColumn('tgl_transfer');
            $table->dropColumn('bukti');
            $table->dropColumn('tipe_pelunasan');
            $table->dropColumn('status_pembayaran');
            $table->dropColumn('status');
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
