<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_pasien', function (Blueprint $table) {
            $table->string('no_reg_pasien')->primary();
            $table->string('nama_pasien');
            $table->integer('umur');
            $table->string('no_ruangan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};