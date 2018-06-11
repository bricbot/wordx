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
            // quiz_flag
            $table->uuid('uuid')->unique();
            $table->string('alias')->nullable();
            // quiz_contents
            $table->string('quiz_ids');
            $table->longText('quizes');
            $table->unsignedTinyInteger('total_pages')->nullable();
            $table->string('correct_template');
            // permit_status
            $table->longText('img_path')->nullable();
            $table->unsignedTinyInteger('permit_self_correct')->nullable()->default(0);
            $table->unsignedTinyInteger('complete_self_correct')->nullable()->default(0);
            // time_logs
            $table->dateTime('create_time');
            $table->date('practice_time')->nullable();
            $table->dateTime('confirm_time')->nullable();
            $table->dateTime('self_correct_time')->nullable();
            $table->dateTime('teacher_correct_time')->nullable();
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
