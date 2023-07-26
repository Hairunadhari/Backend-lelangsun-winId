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
        Schema::table('barang_lelangs', function (Blueprint $table) {
            $table->string('nomer_rangka')->change()->nullable();
            $table->string('nomer_mesin')->change()->nullable();
            $table->string('no_polisi')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_lelangs', function (Blueprint $table) {
            //
        });
    }
};
