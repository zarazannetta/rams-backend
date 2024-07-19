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
        Schema::create('spatial_lhr_polygon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->multiPolygon('geom');
            $table->string('segmen_tol')->nullable();
            $table->string('nama_segmen')->nullable();
            $table->string('gol_i')->nullable();
            $table->string('gol_ii')->nullable();
            $table->string('gol_iii')->nullable();
            $table->string('gol_iv')->nullable();
            $table->string('gol_v')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spatial_lhr_polygon', function (Blueprint $table) {
            $table->dropForeign(['jalan_tol_id']);
        });
        Schema::dropIfExists('spatial_lhr_polygon');
    }
};
