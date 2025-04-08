<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('table_dosis_infus_pasien', function (Blueprint $table) {
            $table->string('id_perangkat_infusee')->nullable()->after('timestamp_infus');

            // Jika ada relasi ke tabel device, tambahkan foreign key
            $table->foreign('id_perangkat_infusee')->references('id_perangkat_infusee')->on('table_perangkat_infusee')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('table_dosis_infus_pasien', function (Blueprint $table) {
            $table->dropForeign(['id_perangkat_infusee']);
            $table->dropColumn('id_perangkat_infusee');
        });
    }

};