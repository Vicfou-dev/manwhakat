<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMangasFollowedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_mangas_followed', function (Blueprint $table) {

            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";

            $table->bigInteger('manga_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();

            $table->foreign('manga_id')->references('id')->on('mangas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        
            //Primary Keys
            $table->primary(['manga_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_mangas_followed');
    }
}