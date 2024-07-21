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
        Schema::create('data_jalan_teknik2_lapis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_lapis_id');
            $table->integer('tahun');
            $table->string('uraian');
            $table->string('nilai_ki_lajur1')->nullable();
            $table->string('nilai_ki_lajur2')->nullable();
            $table->string('nilai_ki_lajur3')->nullable();
            $table->string('nilai_ki_lajur4')->nullable();
            $table->string('nilai_ka_lajur1')->nullable();
            $table->string('nilai_ka_lajur2')->nullable();
            $table->string('nilai_ka_lajur3')->nullable();
            $table->string('nilai_ka_lajur4')->nullable();
            $table->timestamps();

            $table->foreign('jenis_lapis_id')->references('id')->on('reff_jenis_lapis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_teknik2_lapis');
    }
};
