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
        Schema::create('invoice_stores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('exp_date')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('nama_produk')->nullable();
            $table->bigInteger('harga_x_qty')->nullable();
            $table->bigInteger('promosi_id')->nullable();
            $table->bigInteger('harga')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_stores');
    }
};
