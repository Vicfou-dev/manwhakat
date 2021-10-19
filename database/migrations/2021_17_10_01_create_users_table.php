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
            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";
            $table->bigIncrements('id');
            $table->string('username', 64)->unique();
            $table->string("token", 64)->unique();
            $table->string('email', 128)->unique();
            $table->string('password', 128);
            $table->string('image', 128)->default('');
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
        Schema::dropIfExists('users');
    }
}