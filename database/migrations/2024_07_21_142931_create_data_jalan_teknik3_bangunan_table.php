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
        Schema::create('data_jalan_teknik3_bangunan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_bangunan_id');
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('nilai_ke1_ki')->nullable();
            $table->string('nilai_ke1_ka')->nullable();
            $table->string('nilai_ke2_ki')->nullable();
            $table->string('nilai_ke2_ka')->nullable();
            $table->string('nilai_ke3_ki')->nullable();
            $table->string('nilai_ke3_ka')->nullable();
            $table->string('nilai_ke4_ki')->nullable();
            $table->string('nilai_ke4_ka')->nullable();
            $table->timestamps();

            $table->foreign('jenis_bangunan_id')->references('id')->on('reff_jenis_bangunan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_teknik3_bangunan');
    }
};
