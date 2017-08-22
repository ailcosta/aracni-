<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name",120);
            $table->string("alias",5)->nullable;
            $table->string("main_url",225);
            $table->string("login_url",225);
            $table->string("base_url",225);
            $table->string("user",80)->nullable;
            $table->string("password",120)->nullable;
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
