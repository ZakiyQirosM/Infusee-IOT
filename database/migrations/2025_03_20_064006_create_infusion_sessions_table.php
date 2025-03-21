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
            $table->string('no_reg_pasien');
            $table->string('id_perangkat_infusee')->nullable(); // Nullable karena mungkin belum dipilih device
            $table->integer('durasi_infus_menit');
            $table->timestamp('timestamp_infus')->nullable();
            $table->timestamps();

            // 🔗 Foreign Key ke table_pasien
            $table->foreign('no_reg_pasien')
                ->references('no_reg_pasien')
                ->on('table_pasien')    
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // 🔗 Foreign Key ke table_perangkat_infusee
            $table->foreign('id_perangkat_infusee')
                ->references('id_perangkat_infusee')
                ->on('table_perangkat_infusee')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infusion_sessions');
    }
};
