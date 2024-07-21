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
        Schema::create('leger_identifikasi_jalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->string('leger_id');
            $table->unsignedBigInteger('kode_provinsi_id');
            $table->unsignedBigInteger('kode_kabkot_id');
            $table->unsignedBigInteger('kode_kecamatan_id');
            $table->unsignedBigInteger('kode_desakel_id');
            $table->integer('nomor_ruas')->nullable();
            $table->integer('nomor_seksi')->nullable();
            $table->string('deskripsi_seksi')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('titik_ikat_leger_kode')->nullable();
            $table->string('titik_ikat_leger_x')->nullable();
            $table->string('titik_ikat_leger_y')->nullable();
            $table->string('titik_ikat_leger_z')->nullable();
            $table->string('titik_ikat_leger_deskripsi')->nullable();
            $table->string('titik_ikat_patok_kode')->nullable();
            $table->string('titik_ikat_patok_km')->nullable();
            $table->string('titik_ikat_patok_x')->nullable();
            $table->string('titik_ikat_patok_y')->nullable();
            $table->string('titik_ikat_patok_z')->nullable();
            $table->string('titik_ikat_patok_deskripsi')->nullable();
            $table->string('titik_awal_segmen_kode')->nullable();
            $table->string('titik_awal_segmen_km')->nullable();
            $table->string('titik_awal_segmen_x')->nullable();
            $table->string('titik_awal_segmen_y')->nullable();
            $table->string('titik_awal_segmen_z')->nullable();
            $table->string('titik_awal_segmen_deskripsi')->nullable();
            $table->string('titik_akhir_segmen_kode')->nullable();
            $table->string('titik_akhir_segmen_km')->nullable();
            $table->string('titik_akhir_segmen_x')->nullable();
            $table->string('titik_akhir_segmen_y')->nullable();
            $table->string('titik_akhir_segmen_z')->nullable();
            $table->string('titik_akhir_segmen_deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
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
        Schema::dropIfExists('leger_identifikasi_jalan');
    }
};
