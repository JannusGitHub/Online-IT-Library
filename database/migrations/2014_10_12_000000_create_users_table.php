<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
    $table->bigIncrements('id');
    $table->string('name');
    $table->string('position');
    $table->string('username');
    $table->string('password');
    $table->string('is_password_changed');
    $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
    $table->bigInteger('user_level_id')->unsigned();
    $table->timestamps();
    
    // Foreign Key
    $table->foreign('user_level_id')->references('id')->on('user_levels')->onDelete('cascade');

    // $table->rememberToken();
    // $table->string('email')->unique();
    // $table->timestamp('email_verified_at')->nullable();
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
