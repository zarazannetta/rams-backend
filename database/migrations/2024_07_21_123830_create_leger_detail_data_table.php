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
        Schema::create('leger_detail_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_identifikasi_id');
            $table->unsignedBigInteger('detail_data_id');
            $table->string('jenis_leger');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_detail_data');
    }
};
