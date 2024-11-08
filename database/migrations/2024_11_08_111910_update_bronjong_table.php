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
        Schema::table('spatial_bronjong_line', function (Blueprint $table)
        {
            $table->string('jenis_material')->nullable();
            $table->string('ukuran_panjang')->nullable();
            $table->string('kondisi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spatial_bronjong_line', function (Blueprint $table)
        {
            $table->dropColumn('jenis_material');
            $table->dropColumn('ukuran_panjang');
            $table->dropColumn('kondisi');
        });
    }
};
