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
            $table->dropColumn('no_resi');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->bigInteger('tracking_id')->nullable();
            $table->bigInteger('waybill_id')->nullable();
            $table->string('couier_name')->nullable();
            $table->string('couier_phone')->nullable();
            $table->string('couier_link')->nullable();
            $table->bigInteger('insurance_amount')->nullable();
            $table->string('price')->nullable();
            $table->string('status')->nullable();
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
