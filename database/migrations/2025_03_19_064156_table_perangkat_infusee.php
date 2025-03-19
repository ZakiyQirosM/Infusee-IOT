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
        Schema::create('table_perangkat_infusee', function (Blueprint $table) {
            $table->string('id_perangkat_infusee')->primary(); // Primary key tanpa auto-increment
            $table->string('alamat_ip_infusee')->unique(); // Unique key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_perangkat_infusee');
    }
};
