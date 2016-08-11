<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionnaireTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestionnaires', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password',60);
            $table->integer('cooperative_id')->unsigned();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onDelete('cascade');;
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
        Schema::drop('gestionnaires');
    }
}
