<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

    class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('created_by')->index();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('color', [
                'blue',
                'indigo',
                'purple',
                'pink',
                'red',
                'orange',
                'yellow',
                'green',
                'teal',
                'cyan',
            ])->default('blue');
            $table->enum('status', ['active', 'archived', 'both'])->default('active');
            $table->enum('project_status', ['on-going', 'done'])->default('on-going');
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
        Schema::dropIfExists('projects');
    }
}
