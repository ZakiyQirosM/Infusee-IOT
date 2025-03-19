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
        Schema::create('table_perawat', function (Blueprint $table) {
            $table->string('no_pegawai')->primary(); // Primary key tanpa auto-increment
            $table->string('nama_perawat'); // Nama perawat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_perawat');
    }
};
