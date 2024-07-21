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
        Schema::create('data_rumija_koordinat_patok', function (Blueprint $table) {
            $table->id();
            $table->string('no_patok');
            $table->string('koordinat_x');
            $table->string('koordinat_y');
            $table->string('koordinat_z');
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rumija_koordinat_patok');
    }
};
