<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->unsigned(false);
            $table->integer('user_id')->index();
            $table->integer('report_id')->index();
            $table->timestamps();
        });
        Schema::table('roles', function ($table) {
            //foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->on('users')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onUpdate('cascade')->on('reports')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}