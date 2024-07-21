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
        Schema::create('data_jembatan_teknik3_bangunanpengaman', function (Blueprint $table) {
            $table->id();
            $table->string('macam');
            $table->boolean('bahan_pas_batu')->nullable();
            $table->boolean('bahan_pas_bata')->nullable();
            $table->boolean('bahan_kayu')->nullable();
            $table->boolean('bahan_baja')->nullable();
            $table->boolean('bahan_beton')->nullable();
            $table->boolean('bahan_batu_belah')->nullable();
            $table->boolean('bahan_bata')->nullable();
            $table->boolean('kondisi_berkarat')->nullable();
            $table->boolean('kondisi_bergetar')->nullable();
            $table->boolean('kondisi_retak_biasa')->nullable();
            $table->boolean('kondisi_berlubang')->nullable();
            $table->boolean('kondisi_retak_kritis')->nullable();
            $table->boolean('kondisi_tergerus')->nullable();
            $table->boolean('kondisi_bergeser')->nullable();
            $table->boolean('kondisi_patah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jembatan_teknik3_bangunanpengaman');
    }
};
