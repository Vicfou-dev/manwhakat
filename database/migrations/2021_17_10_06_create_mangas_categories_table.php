<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangasCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas_categories', function (Blueprint $table) {

            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";

            $table->bigInteger('manga_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();

            $table->foreign('manga_id')->references('id')->on('mangas')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        
            //Primary Keys
            $table->primary(['manga_id','category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mangas_categories');
    }
}