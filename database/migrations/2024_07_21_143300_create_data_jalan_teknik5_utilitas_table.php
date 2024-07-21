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
        Schema::create('data_jalan_teknik5_utilitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_sarana_id');
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('nilai_ki')->nullable();
            $table->string('nilai_md')->nullable();
            $table->string('nilai_ka')->nullable();
            $table->timestamps();

            $table->foreign('jenis_sarana_id')->references('id')->on('reff_jenis_sarana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_teknik5_utilitas');
    }
};
