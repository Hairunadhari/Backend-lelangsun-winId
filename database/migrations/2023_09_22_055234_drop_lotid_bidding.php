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
        Schema::table('biddings', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biddings', function (Blueprint $table) {
            $table->dropForeign('biddings_lot_id_foreign');
            $table->dropColumn('lot_id');

            $table->unsignedBigInteger('lot_item_id')->nullable()->after('id');
            $table->foreign('lot_item_id')->references('id')->on('lot_items');

        });
    }
};
