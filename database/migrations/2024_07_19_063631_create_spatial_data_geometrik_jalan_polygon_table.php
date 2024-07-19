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
        Schema::create('spatial_data_geometrik_jalan_polygon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->multiPolygon('geom');
            $table->string('id_leger')->nullable();
            $table->string('segmen_tol')->nullable();
            $table->string('nama')->nullable();
            $table->string('lebar_rmj')->nullable();
            $table->string('gradien_kiri')->nullable();
            $table->string('gradien_kanan')->nullable();
            $table->string('cross_fall_kiri')->nullable();
            $table->string('cross_fall_kanan')->nullable();
            $table->string('super_elevasi')->nullable();
            $table->string('radius')->nullable();
            $table->string('terrain_kiri')->nullable();
            $table->string('terrain_kanan')->nullable();
            $table->string('tataguna_lahan_kiri')->nullable();
            $table->string('tataguna_lahan_kanan')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spatial_data_geometrik_lingkungan_jalan_polygon');
    }
};
