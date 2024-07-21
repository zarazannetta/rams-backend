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
        Schema::create('data_jembatan_teknik3_landasan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('uraian');
            $table->boolean('kep_jbt_ki')->nullable();
            $table->boolean('pilar1_ki')->nullable();
            $table->boolean('pilar1_ka')->nullable();
            $table->boolean('pilar2_ki')->nullable();
            $table->boolean('pilar2_ka')->nullable();
            $table->boolean('pilar3_ki')->nullable();
            $table->boolean('pilar3_ka')->nullable();
            $table->boolean('kep_jbt_ka')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jembatan_teknik3_landasan');
    }
};
