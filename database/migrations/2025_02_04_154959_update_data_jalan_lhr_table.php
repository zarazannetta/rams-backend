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
        Schema::table('data_jalan_lhr', function (Blueprint $table) {
            $table->unsignedBigInteger('id_leger_jalan')->nullable()->after('id');
            $table->foreign('id_leger_jalan')->references('id')->on('leger_jalan');
    
            // Drop columns tarif_ki and tarif_ka
            $table->dropColumn(['tarif_ki', 'tarif_ka']);
    
            // Add new column tarif
            $table->string('tarif', 255)->nullable()->after('tarif_ka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_jalan_lhr', function (Blueprint $table) {
            $table->dropForeign(['id_leger_jalan']);
            $table->dropColumn('id_leger_jalan');
    
            // Re-add columns tarif_ki and tarif_ka as string
            $table->string('tarif_ki', 255)->nullable();
            $table->string('tarif_ka', 255)->nullable();
    
            // Drop new column tarif
            $table->dropColumn('tarif');
        });
    }
};
