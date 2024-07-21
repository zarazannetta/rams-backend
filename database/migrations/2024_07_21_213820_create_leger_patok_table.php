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
        Schema::create('leger_patok', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leger_id');
            $table->string('kode_leger');
            $table->unsignedBigInteger('data_patok_id');
            $table->timestamps();

            $table->foreign('leger_id')->references('id')->on('leger');
            $table->foreign('data_patok_id')->references('id')->on('data_patok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leger_patok');
    }
};
