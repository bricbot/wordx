<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_corrections', function (Blueprint $table) {
            $table->increments('id');
            // Essential
            $table->uuid('paper_uuid');
            $table->string('student_id');
            $table->string('img_path')->nullable();
            // Correction Logs
            $table->string('comments');
            $table->string('essential_data');
            $table->longText('correction_details');
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
        Schema::dropIfExists('teacher_corrections');
    }
}
