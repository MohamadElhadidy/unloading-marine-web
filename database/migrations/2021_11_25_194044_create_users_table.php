<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned(false);
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->integer('type')->index()->default('2');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('notification_preference')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        schema::table('users', function ($table) {
            //foreign keys
            $table->foreign('type')->references('id')->on('auth')->onUpdate('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}