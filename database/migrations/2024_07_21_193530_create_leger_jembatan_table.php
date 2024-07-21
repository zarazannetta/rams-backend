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
        Schema::create('leger_jembatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_jembatan_identifikasi_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_umum_uraian_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_umum_elevasi_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik1_bangunanatas_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik1_bangunanbawah_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik1_pondasi_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik2_bangunanatas_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik2_bangunanbawah_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik2_pondasi_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik3_landasan_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_teknik3_bangunanpengaman_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_kondisi_umum_id')->nullable();
            $table->unsignedBigInteger('data_jembatan_realisasi_id')->nullable();
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_jembatan_identifikasi_id')->references('id')->on('data_jembatan_identifikasi');
            $table->foreign('data_jembatan_umum_uraian_id')->references('id')->on('data_jembatan_umum_uraian');
            $table->foreign('data_jembatan_umum_elevasi_id')->references('id')->on('data_jembatan_umum_elevasi');
            $table->foreign('data_jembatan_teknik1_bangunanatas_id')->references('id')->on('data_jembatan_teknik1_bangunanatas');
            $table->foreign('data_jembatan_teknik1_bangunanbawah_id')->references('id')->on('data_jembatan_teknik1_bangunanbawah');
            $table->foreign('data_jembatan_teknik1_pondasi_id')->references('id')->on('data_jembatan_teknik1_pondasi');
            $table->foreign('data_jembatan_teknik2_bangunanatas_id')->references('id')->on('data_jembatan_teknik2_bangunanatas');
            $table->foreign('data_jembatan_teknik2_bangunanbawah_id')->references('id')->on('data_jembatan_teknik2_bangunanbawah');
            $table->foreign('data_jembatan_teknik2_pondasi_id')->references('id')->on('data_jembatan_teknik2_pondasi');
            $table->foreign('data_jembatan_teknik3_landasan_id')->references('id')->on('data_jembatan_teknik3_landasan');
            $table->foreign('data_jembatan_teknik3_bangunanpengaman_id')->references('id')->on('data_jembatan_teknik3_bangunanpengaman');
            $table->foreign('data_jembatan_kondisi_umum_id')->references('id')->on('data_jembatan_kondisi_umum');
            $table->foreign('data_jembatan_realisasi_id')->references('id')->on('data_jembatan_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_jembatan');
    }
};
