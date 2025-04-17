<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peg');
            $table->string('no_peg')->unique(); // no_peg
            $table->string('password');
            $table->string('no_wa'); // Nomor WhatsApp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_pegawai');
    }
};
