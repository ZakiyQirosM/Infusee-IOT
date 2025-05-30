<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infusion_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_session')->primary()->autoIncrement();
            $table->string('no_peg');
            $table->string('no_reg_pasien');
            $table->string('id_perangkat_infusee')->nullable();
            $table->integer('durasi_infus_jam');
            $table->timestamp('timestamp_infus')->nullable();
            $table->timestamps();
            $table->string('status_sesi_infus')->default('active');
        
            $table->foreign('no_peg')
                ->references('no_peg')
                ->on('table_pegawai')
                ->onDelete('cascade');
                
            $table->foreign('no_reg_pasien')
                ->references('no_reg_pasien')
                ->on('table_pasien')    
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_perangkat_infusee')
                ->references('id_perangkat_infusee')
                ->on('table_perangkat_infusee')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infusion_sessions');
    }
};
