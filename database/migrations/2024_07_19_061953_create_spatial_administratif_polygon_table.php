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
        Schema::create('spatial_administratif_polygon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->multiPolygonZ('geom');
            $table->string('txtmemo')->nullable();
            $table->string('kode_prov')->nullable();
            $table->string('nama_prov')->nullable();
            $table->string('kode_kab')->nullable();
            $table->string('nama_kab')->nullable();
            $table->string('kode_kec')->nullable();
            $table->string('nama_kec')->nullable();
            $table->string('kode_desa')->nullable();
            $table->string('nama_desa')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spatial_administratif_polygon', function (Blueprint $table) {
            $table->dropForeign(['jalan_tol_id']);
        });
        Schema::dropIfExists('spatial_administratif_polygon');
    }
};
