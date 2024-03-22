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
            $table->string('detail_alamat_user')->nullable();
            $table->bigInteger('postal_code_user')->nullable();
            $table->string('nama_user')->nullable();
            $table->bigInteger('no_telephone_user')->nullable();
            $table->string('email_user')->nullable();
            $table->string('nama_pemilik_toko')->nullable();
            $table->bigInteger('no_telephone_toko')->nullable();
            $table->string('detail_alamat_toko')->nullable();
            $table->bigInteger('postal_code_toko')->nullable();
            $table->string('nama_toko')->nullable();
            $table->string('courier_company')->nullable();
            $table->string('courier_service_code')->nullable();
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
