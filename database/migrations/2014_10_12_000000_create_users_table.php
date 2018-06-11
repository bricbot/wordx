<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // Essential
            $table->increments('id');
            $table->string('account')->unique();
            $table->string('email')->unique();
            $table->string('password');
            // Personality
            $table->string('role');
            $table->string('real_name')->nullable();
            $table->string('permit')->nullable();
            // Misc.
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
