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
        Schema::create('spatial_iri_polygon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jalan_tol_id');
            $table->multiPolygon('geom');
            $table->string('jalur')->nullable();
            $table->string('bagian_jalan')->nullable();
            $table->string('lebar')->nullable();
            $table->string('segmen_tol')->nullable();
            $table->string('km')->nullable();
            $table->float('nilai_iri')->nullable();
            $table->timestamps();

            $table->foreign('jalan_tol_id')->references('id')->on('jalan_tol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spatial_iri_polygon');
    }
};
