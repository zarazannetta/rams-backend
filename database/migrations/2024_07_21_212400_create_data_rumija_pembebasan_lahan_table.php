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
        Schema::create('data_rumija_pembebasan_lahan', function (Blueprint $table) {
            $table->id();
            $table->string('desabidang');
            $table->string('luasan')->nullable();
            $table->string('sumber_pendanaan')->nullable();
            $table->string('nilai_perolehan')->nullable();
            $table->string('dokumen_perolehan')->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->string('tanggal_dokumen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rumija_pembebasan_lahan');
    }
};
