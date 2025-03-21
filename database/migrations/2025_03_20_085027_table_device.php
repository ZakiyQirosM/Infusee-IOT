<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_perangkat_infusee', function (Blueprint $table) {
            $table->string('id_perangkat_infusee')->primary();
            $table->string('alamat_id_infusee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};