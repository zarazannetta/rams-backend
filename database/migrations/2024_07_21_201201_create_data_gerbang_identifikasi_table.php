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
        Schema::create('data_gerbang_identifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_provinsi_id');
            $table->unsignedBigInteger('kode_kabkot_id');
            $table->unsignedBigInteger('kode_kecamatan_id');
            $table->unsignedBigInteger('kode_desakel_id');
            $table->integer('nomor_ruas')->nullable();
            $table->integer('nomor_seksi')->nullable();
            $table->string('nama_ruas')->nullable();
            $table->string('nama_kawasan_kantor')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('titik_ikat_leger_kode')->nullable();
            $table->string('titik_ikat_leger_x')->nullable();
            $table->string('titik_ikat_leger_y')->nullable();
            $table->string('titik_ikat_leger_z')->nullable();
            $table->string('titik_ikat_leger_deskripsi')->nullable();
            $table->string('tanggal_selesai_bangun')->nullable();
            $table->string('tanggal_dibuka')->nullable();
            $table->string('tanggal_ditutup')->nullable();
            $table->timestamps();

            $table->foreign('kode_provinsi_id')->references('id')->on('reff_kode_provinsi');
            $table->foreign('kode_kabkot_id')->references('id')->on('reff_kode_kabkot');
            $table->foreign('kode_kecamatan_id')->references('id')->on('reff_kode_kecamatan');
            $table->foreign('kode_desakel_id')->references('id')->on('reff_kode_desakel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gerbang_identifikasi');
    }
};
