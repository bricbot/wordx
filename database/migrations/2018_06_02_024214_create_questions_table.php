<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            // Locate
            $table->string('subject');            
            $table->string('book');
            $table->string('unit')->nullable();
            $table->string('chapter')->nullable();
            $table->string('list')->nullable();
            $table->string('page')->nullable();
            $table->string('number');
            // Quiz
            $table->string('quiz');
            $table->string('key')->nullable();
            // Misc.
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
        Schema::dropIfExists('questions');
    }
}
