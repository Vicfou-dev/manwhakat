<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";
            $table->bigIncrements('id');
            $table->bigInteger('chapter_id')->unsigned();
            $table->string('title', 512)->unique();
            $table->string("outer_link", 512)->default('');
            $table->string("inner_link", 512)->default('');
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
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
        Schema::dropIfExists('images');
    }
}