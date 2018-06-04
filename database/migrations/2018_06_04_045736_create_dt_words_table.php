<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dt_words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alias');
            $table->string('book');
            $table->string('category');
            $table->integer('page');
            $table->integer('list');
            $table->integer('number');
            $table->string('word');
            $table->string('symbol');
            $table->string('pos');
            $table->string('meaning');
            $table->string('omeaning');
            $table->string('extra');
            $table->string('operating_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dt_words');
    }
}
