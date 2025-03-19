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
        Schema::create('table_dosis_infus_pasien', function (Blueprint $table) {
            $table->string('fk_no_reg_pasien'); // Foreign key ke pasien
            $table->string('fk_no_pegawai'); // Foreign key ke perawat
            $table->string('fk_id_perangkat_infusee'); // Foreign key ke perangkat infus
            $table->text('dosis_infus');
            $table->integer('laju_tetes_tpm');
            $table->integer('persentase_infus_menit');
            $table->boolean('status_anomali_infus');
            $table->integer('durasi_infus_menit');
            $table->timestamp('timestamp_infus')->useCurrent();

            // Definisi Foreign Key
            $table->foreign('fk_no_reg_pasien')->references('no_reg_pasien')->on('table_pasien')->onDelete('cascade');
            $table->foreign('fk_no_pegawai')->references('no_pegawai')->on('table_perawat')->onDelete('cascade');
            $table->foreign('fk_id_perangkat_infusee')->references('id_perangkat_infusee')->on('table_perangkat_infusee')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_dosis_infus_pasien');
    }
};
