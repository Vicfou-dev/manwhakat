<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";
            $table->bigIncrements('id');
            $table->bigInteger('manga_id')->unsigned();
            $table->string('name', 512)->unique();
            $table->string("outer_link", 512)->default('');
            $table->string("inner_link", 512)->default('');
            $table->string("numerotation", 256)->default('');
            $table->datetime("upload");
            $table->foreign('manga_id')->references('id')->on('mangas')->onDelete('cascade');
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
        Schema::dropIfExists('chapters');
    }
}