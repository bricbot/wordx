<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistantConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistant_confirms', function (Blueprint $table) {
            $table->increments('id');
            // Essential
            $table->uuid('paper_uuid');
            $table->string('student_id');
            $table->unsignedTinyInteger('img_order')->nullable();
            $table->string('img_path')->nullable();
            // Correction
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
        Schema::dropIfExists('assistant_confirms');
    }
}
