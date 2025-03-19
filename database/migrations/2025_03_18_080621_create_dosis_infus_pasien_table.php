<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_dosis_infus_pasien', function (Blueprint $table) {
            $table->id('no_reg_pasien');
            $table->integer('no_pegawai');
            $table->string('id_perangkat_infusee');
            $table->text('dosis_infus');
            $table->integer('laju_tetes_tpm');
            $table->integer('persentase_infus_menit');
            $table->string('status_anomali_infus');
            $table->timestamps('timestamp_infus');

            // Foreign key constraints
            $table->foreign('no_reg_pasien')->references('no_reg_pasien')->on('table_pasien')->onDelete('cascade');
            $table->foreign('no_pegawai')->references('no_pegawai')->on('table_perawat')->onDelete('cascade');
            $table->foreign('id_perangkat_infusee')->references('id_perangkat_infusee')->on('table_perangkat_infusee')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_dosis_infus_pasien');
    }
};
