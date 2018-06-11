<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelfCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_corrections', function (Blueprint $table) {
            $table->increments('id');
            // Essential
            $table->uuid('paper_uuid');
            $table->string('student_id');
            $table->string('img_path')->nullable();
            // Correction
            $table->string('quiz_ids');
            $table->longText('self_corrections');
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
        Schema::dropIfExists('self_corrections');
    }
}
