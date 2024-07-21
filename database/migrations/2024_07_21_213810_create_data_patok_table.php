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
        Schema::create('data_patok', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_patok');
            $table->integer('nomor_ruas')->nullable();
            $table->integer('nomor_seksi')->nullable();
            $table->string('nama_ruas');
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('provinsi');
            $table->string('lokasi');
            $table->string('tahun_pemasangan');
            $table->string('koordinat_x');
            $table->string('koordinat_y');
            $table->string('koordinat_z');
            $table->string('sistem_koordinat');
            $table->string('tipe_alat_ukur');
            $table->string('tanggal_pengukuran');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_patok');
    }
};
