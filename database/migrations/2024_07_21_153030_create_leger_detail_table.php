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
        Schema::create('leger_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->unsignedBigInteger('user_id');
            $table->string('leger_id');
            $table->string('jenis_leger');
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

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_detail');
    }
};
