<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_perawat', function (Blueprint $table) {
            $table->integer('no_pegawai')->primary();
            $table->string('nama_perawat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawat');
    }
};