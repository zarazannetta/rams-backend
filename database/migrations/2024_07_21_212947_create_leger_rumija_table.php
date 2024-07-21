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
        Schema::create('leger_rumija', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_rumija_id')->nullable();
            $table->unsignedBigInteger('data_rumija_koordinat_patok_id')->nullable();
            $table->unsignedBigInteger('data_rumija_pembebasan_lahan_id')->nullable();
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_rumija_id')->references('id')->on('data_rumija');
            $table->foreign('data_rumija_koordinat_patok_id')->references('id')->on('data_rumija_koordinat_patok');
            $table->foreign('data_rumija_pembebasan_lahan_id')->references('id')->on('data_rumija_pembebasan_lahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_rumija');
    }
};
