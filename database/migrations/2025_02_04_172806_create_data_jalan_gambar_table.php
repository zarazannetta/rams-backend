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
        Schema::create('data_jalan_gambar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_leger_jalan');
            $table->string('tahun', 255);
            $table->string('path_alinyemen_horizontal', 255);
            $table->string('path_alinyemen_vertikal', 255);
            $table->string('path_penampang_melintang', 255);
            $table->timestamps();
    
            // Foreign key constraint
            $table->foreign('id_leger_jalan')->references('id')->on('leger_jalan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_gambar');
    }
};
