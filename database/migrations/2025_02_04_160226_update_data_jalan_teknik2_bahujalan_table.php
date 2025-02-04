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
        Schema::table('data_jalan_teknik2_bahujalan', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_lapis_id')->nullable()->after('id');
            $table->unsignedBigInteger('id_leger_jalan')->nullable()->after('id');

            $table->foreign('jenis_lapis_id')->references('id')->on('reff_jenis_lapis');
            $table->foreign('id_leger_jalan')->references('id')->on('leger_jalan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_jalan_teknik2_bahujalan', function (Blueprint $table) {
            $table->dropForeign(['jenis_lapis_id']);
            $table->dropForeign(['id_leger_jalan']);

            $table->dropColumn('jenis_lapis_id');
            $table->dropColumn('id_leger_jalan');
        });
    }
};
