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
        Schema::create('leger_jalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_jalan_identifikasi_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik1_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik2_lapis_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik2_median_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik2_bahujalan_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik3_goronggorong_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik3_saluran_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik3_bangunan_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik4_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik5_utilitas_id')->nullable();
            $table->unsignedBigInteger('data_jalan_teknik5_bangunan_id')->nullable();
            $table->unsignedBigInteger('data_jalan_lhr_id')->nullable();
            $table->unsignedBigInteger('data_jalan_geometrik_id')->nullable();
            $table->unsignedBigInteger('data_jalan_lingkungan_id')->nullable();
            $table->unsignedBigInteger('data_jalan_lainnya_id')->nullable();
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_jalan_identifikasi_id')->references('id')->on('data_jalan_identifikasi');
            $table->foreign('data_jalan_teknik1_id')->references('id')->on('data_jalan_teknik1');
            $table->foreign('data_jalan_teknik2_lapis_id')->references('id')->on('data_jalan_teknik2_lapis');
            $table->foreign('data_jalan_teknik2_median_id')->references('id')->on('data_jalan_teknik2_median');
            $table->foreign('data_jalan_teknik2_bahujalan_id')->references('id')->on('data_jalan_teknik2_bahujalan');
            $table->foreign('data_jalan_teknik3_goronggorong_id')->references('id')->on('data_jalan_teknik3_goronggorong');
            $table->foreign('data_jalan_teknik3_saluran_id')->references('id')->on('data_jalan_teknik3_saluran');
            $table->foreign('data_jalan_teknik3_bangunan_id')->references('id')->on('data_jalan_teknik3_bangunan');
            $table->foreign('data_jalan_teknik4_id')->references('id')->on('data_jalan_teknik4');
            $table->foreign('data_jalan_teknik5_utilitas_id')->references('id')->on('data_jalan_teknik5_utilitas');
            $table->foreign('data_jalan_teknik5_bangunan_id')->references('id')->on('data_jalan_teknik5_bangunan');
            $table->foreign('data_jalan_lhr_id')->references('id')->on('data_jalan_lhr');
            $table->foreign('data_jalan_geometrik_id')->references('id')->on('data_jalan_geometrik');
            $table->foreign('data_jalan_lingkungan_id')->references('id')->on('data_jalan_lingkungan');
            $table->foreign('data_jalan_lainnya_id')->references('id')->on('data_jalan_lainnya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_jalan');
    }
};
