<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_pegawai', function (Blueprint $table) {
            $table->string('nama_peg');
            $table->string('no_peg')->primary();
            $table->string('password');
            $table->string('no_wa');
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_pegawai');
    }
};
