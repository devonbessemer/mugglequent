<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments("id");
            $table->string("first_name");
            $table->string("last_name");
            $table->integer("age");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments("id");
            $table->string("city");
            $table->unsignedInteger("user_id");
        });
    }

    public function down()
    {

    }
}