<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_dosis_infus_pasien', function (Blueprint $table) {
            $table->unsignedBigInteger('id_session'); // ✅ Jadikan id_session sebagai primary key
            $table->integer('dosis_infus'); // ✅ Dosis infus
            $table->integer('laju_tetes_tpm'); // ✅ Laju tetesan per menit
            $table->integer('persentase_infus_menit'); // ✅ Persentase sisa infus
            $table->string('status_anomali_infus')->default('Normal'); // ✅ Status anomali infus
            $table->timestamp('timestamp_infus')->nullable(); // ✅ Waktu infus
            $table->timestamps(); // ✅ created_at dan updated_at otomatis

            // ✅ Foreign key ke tabel infusion_sessions
            $table->foreign('id_session')
                ->references('id_session')
                ->on('infusion_sessions')    
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_dosis_infus_pasien');
    }
};
