<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->collation = "utf8mb4_unicode_ci";
            $table->bigIncrements('id');
            $table->string('name', 512)->unique();
            $table->string("outer_link", 512)->default('');
            $table->string("inner_link", 512)->default('');
            $table->string("status", 64);
            $table->text("description");
            $table->datetime("last_updated");
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
        Schema::dropIfExists('mangas');
    }
}