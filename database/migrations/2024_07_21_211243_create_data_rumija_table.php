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
        Schema::create('data_rumija', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_provinsi_id');
            $table->unsignedBigInteger('kode_kabkot_id');
            $table->string('nama_ruas')->nullable();
            $table->string('awal_segmen')->nullable();
            $table->string('akhir_segmen')->nullable();
            $table->string('desakel');
            $table->string('luasan_apbn')->nullable();
            $table->string('nilai_perolehan_apbn')->nullable();
            $table->string('luasan_nonapbn')->nullable();
            $table->string('nilai_perolehan_nonapbn')->nullable();
            $table->string('kebutuhan_lahan_ugr')->nullable();
            $table->string('kebutuhan_lahan_nonugr')->nullable();
            $table->string('kebutuhan_lahan_total')->nullable();
            $table->string('pengukuran_lapangan_tol_ugr')->nullable();
            $table->string('pengukuran_lapangan_tol_nonugr')->nullable();
            $table->string('pengukuran_lapangan_tol_total')->nullable();
            $table->string('pengukuran_lapangan_nontol_ugr')->nullable();
            $table->string('pengukuran_lapangan_nontol_nonugr')->nullable();
            $table->string('pengukuran_lapangan_nontol_total')->nullable();
            $table->string('total_luasan')->nullable();
            $table->string('luasan_sertifikat')->nullable();
            $table->string('lembar')->nullable();
            $table->timestamps();

            $table->foreign('kode_provinsi_id')->references('id')->on('reff_kode_provinsi');
            $table->foreign('kode_kabkot_id')->references('id')->on('reff_kode_kabkot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rumija');
    }
};
