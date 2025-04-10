<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_monitoring_infus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_session');
            $table->float('berat_total');
            $table->float('berat_sekarang')->nullable();        // Berat real-time dari sensor loadcell
            $table->integer('tpm_sensor');       // TPM aktual dari sensor opto
            $table->float('tpm_prediksi')->nullable();         // TPM hasil prediksi AI
            $table->timestamp('waktu')->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('id_session')
                  ->references('id_session')
                  ->on('infusion_sessions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_infus');
    }
};

