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
        Schema::create('data_jembatan_umum_elevasi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_elevasi');
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('nilai_hulu')->nullable();
            $table->string('nilai_hilir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jembatan_umum_elevasi');
    }
};
