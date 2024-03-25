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
            $table->json('available_collection_method')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('courier_service_name')->nullable();
            $table->string('description')->nullable();
            $table->string('duration')->nullable();
            $table->string('service_type')->nullable();
            $table->string('shipping_type')->nullable();
            $table->bigInteger('price')->nullable();
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
