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
        Schema::table('gambar_lelangs', function (Blueprint $table) {
            $table->dropForeign('gambar_lelangs_baranglelang_id_foreign');
            $table->dropColumn('baranglelang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gambar_lelangs', function (Blueprint $table) {
            //
        });
    }
};
