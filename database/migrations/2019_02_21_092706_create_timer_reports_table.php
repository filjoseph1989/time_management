<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimerReportsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * !note: Dili ata ni need
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timer_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamp('time_invested')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timer_reports');
    }
}
