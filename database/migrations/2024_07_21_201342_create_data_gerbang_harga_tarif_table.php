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
        Schema::create('data_gerbang_harga_tarif', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('gerbang');
            $table->string('gol_1')->nullable();
            $table->string('gol_2')->nullable();
            $table->string('gol_3')->nullable();
            $table->string('gol_4')->nullable();
            $table->string('gol_5')->nullable();
            $table->string('gol_6')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gerbang_harga_tarif');
    }
};
