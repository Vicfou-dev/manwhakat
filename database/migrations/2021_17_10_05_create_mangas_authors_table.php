<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangasAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas_authors', function (Blueprint $table) {

            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";

            $table->bigInteger('manga_id')->unsigned();
            $table->bigInteger('author_id')->unsigned();

            $table->foreign('manga_id')->references('id')->on('mangas')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
        
            //Primary Keys
            $table->primary(['manga_id','author_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mangas_authors');
    }
}