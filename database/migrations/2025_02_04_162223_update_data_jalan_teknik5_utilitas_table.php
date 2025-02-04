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
        Schema::table('data_jalan_teknik5_utilitas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_leger_jalan')->nullable()->after('id');
            $table->foreign('id_leger_jalan')->references('id')->on('leger_jalan');
    
            // Drop foreign key constraint for jenis_sarana_id
            $table->dropForeign(['jenis_sarana_id']);
            $table->dropColumn('jenis_sarana_id');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_jalan_teknik5_utilitas', function (Blueprint $table) {
            $table->dropForeign(['id_leger_jalan']);
            $table->dropColumn('id_leger_jalan');
    
            // Re-add foreign key constraint for jenis_sarana_id
            $table->unsignedBigInteger('jenis_sarana_id')->nullable();
            $table->foreign('jenis_sarana_id')->references('id')->on('reff_jenis_sarana');
        });
    }
    
};
