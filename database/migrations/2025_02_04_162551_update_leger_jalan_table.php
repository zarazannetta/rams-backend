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
        Schema::table('leger_jalan', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['data_jalan_identifikasi_id']);
            $table->dropForeign(['data_jalan_teknik1_id']);
            $table->dropForeign(['data_jalan_teknik2_lapis_id']);
            $table->dropForeign(['data_jalan_teknik2_median_id']);
            $table->dropForeign(['data_jalan_teknik2_bahujalan_id']);
            $table->dropForeign(['data_jalan_teknik3_goronggorong_id']);
            $table->dropForeign(['data_jalan_teknik3_saluran_id']);
            $table->dropForeign(['data_jalan_teknik3_bangunan_id']);
            $table->dropForeign(['data_jalan_teknik4_id']);
            $table->dropForeign(['data_jalan_teknik5_utilitas_id']);
            $table->dropForeign(['data_jalan_teknik5_bangunan_id']);
            $table->dropForeign(['data_jalan_lhr_id']);
            $table->dropForeign(['data_jalan_geometrik_id']);
            $table->dropForeign(['data_jalan_lingkungan_id']);
            $table->dropForeign(['data_jalan_lainnya_id']);

            // Drop columns
            $table->dropColumn([
                'data_jalan_identifikasi_id',
                'data_jalan_teknik1_id',
                'data_jalan_teknik2_lapis_id',
                'data_jalan_teknik2_median_id',
                'data_jalan_teknik2_bahujalan_id',
                'data_jalan_teknik3_goronggorong_id',
                'data_jalan_teknik3_saluran_id',
                'data_jalan_teknik3_bangunan_id',
                'data_jalan_teknik4_id',
                'data_jalan_teknik5_utilitas_id',
                'data_jalan_teknik5_bangunan_id',
                'data_jalan_lhr_id',
                'data_jalan_geometrik_id',
                'data_jalan_lingkungan_id',
                'data_jalan_lainnya_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leger_jalan', function (Blueprint $table) {
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
};
