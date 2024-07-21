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
        Schema::create('data_jembatan_teknik2_bangunanatas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_konstruksi_id');
            $table->string('tipe');
            $table->string('uraian');
            $table->boolean('bentang1')->nullable();
            $table->boolean('bentang2')->nullable();
            $table->boolean('bentang3')->nullable();
            $table->boolean('bentang4')->nullable();
            $table->timestamps();

            $table->foreign('jenis_konstruksi_id')->references('id')->on('reff_jenis_konstruksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jembatan_teknik2_bangunanatas');
    }
};
