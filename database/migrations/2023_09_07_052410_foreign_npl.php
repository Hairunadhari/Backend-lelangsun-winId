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
        Schema::table('npls', function (Blueprint $table) {
            $table->unsignedBigInteger('peserta_npl_id')->nullable();
            $table->foreign('peserta_npl_id')->references('id')->on('peserta_npls');

            $table->unsignedBigInteger('pembelian_npl_id')->nullable();
            $table->foreign('pembelian_npl_id')->references('id')->on('pembelian_npls');

            $table->unsignedBigInteger('event_lelang_id')->nullable();
            $table->foreign('event_lelang_id')->references('id')->on('event_lelangs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('npls', function (Blueprint $table) {
            //
        });
    }
};
