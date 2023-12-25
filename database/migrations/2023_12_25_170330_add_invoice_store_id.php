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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_store_id')->nullable()->after('id');
            $table->foreign('invoice_store_id')->references('id')->on('invoice_stores');
            $table->dropForeign('pembayarans_tagihan_id_foreign');
            $table->dropColumn('tagihan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            //
        });
    }
};
