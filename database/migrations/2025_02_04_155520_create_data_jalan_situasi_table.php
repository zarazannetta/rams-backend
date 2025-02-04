<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_jalan_situasi', function (Blueprint $table) {
            $table->id(); // BigInt Primary Key
            $table->unsignedBigInteger('id_leger_jalan')->nullable();
            $table->string('tahun', 255);
            $table->string('uraian', 255);
            $table->string('nilai', 255);
            $table->timestamps(); // created_at & updated_at

            // Foreign key constraint
            $table->foreign('id_leger_jalan')->references('id')->on('leger_jalan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_jalan_situasi');
    }
};
