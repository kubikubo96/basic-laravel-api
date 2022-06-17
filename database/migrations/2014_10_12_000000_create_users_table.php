<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->string('username')->unique();
            $table->string('password');
            $table->tinyInteger('admin')->default(0)->nullable();
            $table->tinyInteger('active')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->string('email')->unique();
            $table->integer('email_verified_time')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->integer('phone_number_verified_time')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->rememberToken();
            $table->softDeletes();
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
