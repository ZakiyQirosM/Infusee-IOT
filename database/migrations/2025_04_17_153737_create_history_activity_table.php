<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryActivityTable extends Migration
{
    public function up()
    {
        Schema::create('history_activity', function (Blueprint $table) {
            $table->id('id_hist_act');
            $table->unsignedBigInteger('id_session');
            $table->string('no_peg');
            $table->text('aktivitas')->default('active');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_session')
                ->references('id_session')
                ->on('infusion_sessions')    
                ->onDelete('cascade');

            $table->foreign('no_peg')
                ->references('no_peg')
                ->on('table_pegawai')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('history_activity');
    }
}
