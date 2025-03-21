
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_dosis_infus_pasien', function (Blueprint $table) {
            // ✅ no_reg_pasien sebagai string (jika varchar di tabel pasien)
            $table->string('no_reg_pasien'); 
            
            // ✅ no_pegawai sebagai integer (jika integer di tabel perawat)
            $table->integer('no_pegawai');

            // ✅ id_perangkat_infusee sebagai string (jika varchar di tabel perangkat)
            $table->string('id_perangkat_infusee');

            $table->text('dosis_infus'); 
            $table->integer('laju_tetes_tpm'); 
            $table->integer('persentase_infus_menit'); 
            $table->string('status_anomali_infus'); 

            // ✅ Gunakan `timestamp()` untuk menyimpan waktu
            $table->timestamp('timestamp_infus')->nullable();

            // ✅ Foreign key constraints
            $table->foreign('no_reg_pasien')->references('no_reg_pasien')->on('table_pasien')->onDelete('cascade');
            $table->foreign('no_pegawai')->references('no_pegawai')->on('table_perawat')->onDelete('cascade');
            $table->foreign('id_perangkat_infusee')->references('id_perangkat_infusee')->on('table_perangkat_infusee')->onDelete('cascade');

            // ✅ Tambahkan timestamps otomatis jika perlu
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_dosis_infus_pasien');
    }
};
