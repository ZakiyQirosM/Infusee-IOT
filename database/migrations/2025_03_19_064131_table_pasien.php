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
        Schema::create('table_pasien', function (Blueprint $table) {
            $table->string('no_reg_pasien')->primary(); // PK tanpa auto-increment
            $table->string('nama_pasien');
            $table->integer('umur'); // Menggunakan integer untuk umur
            $table->string('no_ruangan')->unique(); // Unique key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pasien');
    }
};
