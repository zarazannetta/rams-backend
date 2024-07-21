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
        Schema::create('data_jalan_teknik2_bahujalan', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('nilai_ki_dalam')->nullable();
            $table->string('nilai_ki_luar')->nullable();
            $table->string('nilai_ka_dalam')->nullable();
            $table->string('nilai_ka_luar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_teknik2_bahujalan');
    }
};
