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
        Schema::create('spatial_segmen_seksi_polygon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->multiPolygon('geom');
            $table->string('no_ruas')->nullable();
            $table->string('nama_ruas')->nullable();
            $table->string('seksi')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('km_awal')->nullable();
            $table->string('km_akhir')->nullable();
            $table->string('sta_awal')->nullable();
            $table->string('sta_akhir')->nullable();
            $table->string('x_awal')->nullable();
            $table->string('x_akhir')->nullable();
            $table->string('y_awal')->nullable();
            $table->string('y_akhir')->nullable();
            $table->string('z_awal')->nullable();
            $table->string('z_akhir')->nullable();
            $table->string('deskripsi_awal')->nullable();
            $table->string('deskripsi_akhir')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spatial_segmen_seksi_polygon', function (Blueprint $table) {
            $table->dropForeign(['jalan_tol_id']);
        });
        Schema::dropIfExists('spatial_segmen_seksi_polygon');
    }
};
