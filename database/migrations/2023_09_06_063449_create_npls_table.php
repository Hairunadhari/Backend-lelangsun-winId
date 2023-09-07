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
        Schema::create('npls', function (Blueprint $table) {
            $table->id();
            $table->string('no_npl')->nullable();
            $table->string('npl')->nullable();
            $table->string('status_npl')->default(1);
            $table->string('status')->default('active');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npls');
    }
};
