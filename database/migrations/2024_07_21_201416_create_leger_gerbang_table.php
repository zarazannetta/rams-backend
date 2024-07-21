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
        Schema::create('leger_gerbang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_gerbang_identifikasi_id')->nullable();
            $table->unsignedBigInteger('data_gerbang_teknik1_id')->nullable();
            $table->unsignedBigInteger('data_gerbang_luas_lahan_id')->nullable();
            $table->unsignedBigInteger('data_gerbang_teknik2_id')->nullable();
            $table->unsignedBigInteger('data_gerbang_harga_tarif_id')->nullable();
            $table->unsignedBigInteger('data_gerbang_realisasi_id')->nullable();
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_gerbang_identifikasi_id')->references('id')->on('data_gerbang_identifikasi');
            $table->foreign('data_gerbang_teknik1_id')->references('id')->on('data_gerbang_teknik1');
            $table->foreign('data_gerbang_luas_lahan_id')->references('id')->on('data_gerbang_luas_lahan');
            $table->foreign('data_gerbang_teknik2_id')->references('id')->on('data_gerbang_teknik2');
            $table->foreign('data_gerbang_harga_tarif_id')->references('id')->on('data_gerbang_harga_tarif');
            $table->foreign('data_gerbang_realisasi_id')->references('id')->on('data_gerbang_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_gerbang');
    }
};
