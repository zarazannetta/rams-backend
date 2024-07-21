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
        Schema::create('data_gerbang_luas_lahan', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('luas')->nullable();
            $table->string('data_perolehan')->nullable();
            $table->string('nilai_perolehan')->nullable();
            $table->string('bukti_perolehan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_gerbang_luas_lahan');
    }
};
