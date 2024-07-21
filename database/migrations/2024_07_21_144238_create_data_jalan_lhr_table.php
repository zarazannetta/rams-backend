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
        Schema::create('data_jalan_lhr', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('lhr_ki')->nullable();
            $table->string('lhr_ka')->nullable();
            $table->string('tarif_ki')->nullable();
            $table->string('tarif_ka')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_lhr');
    }
};
