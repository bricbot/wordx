<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->increments('id');
            // quiz_indexes
            $table->string('quiz_ids');
            $table->longText('quizes');
            // time_logs
            $table->dateTime('create_time');
            $table->date('check_time');
            $table->dateTime('complete_time');
            $table->dateTime('self_correct_time');
            $table->dateTime('correct_time');
            // oprater_logs
            $table->string('teacher_id');
            $table->string('assistant_id');
            $table->string('student_id');
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
        Schema::dropIfExists('papers');
    }
}
