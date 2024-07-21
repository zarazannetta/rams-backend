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
        Schema::create('leger_kantor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_kantor_identifikasi_id')->nullable();
            $table->unsignedBigInteger('data_kantor_luas_lahan_id')->nullable();
            $table->unsignedBigInteger('data_kantor_teknik1_id')->nullable();
            $table->unsignedBigInteger('data_kantor_teknik2_id')->nullable();
            $table->unsignedBigInteger('data_kantor_realisasi_id')->nullable();
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_kantor_identifikasi_id')->references('id')->on('data_kantor_identifikasi');
            $table->foreign('data_kantor_luas_lahan_id')->references('id')->on('data_kantor_luas_lahan');
            $table->foreign('data_kantor_teknik1_id')->references('id')->on('data_kantor_teknik1');
            $table->foreign('data_kantor_teknik2_id')->references('id')->on('data_kantor_teknik2');
            $table->foreign('data_kantor_realisasi_id')->references('id')->on('data_kantor_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_kantor');
    }
};
