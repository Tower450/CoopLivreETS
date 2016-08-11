<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationRemiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_remise', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('etudiants_id')->unsigned();
            $table->foreign('etudiants_id')->references('id')->on('etudiants')->onDelete('cascade');
            $table->string('isbn');
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
        Schema::drop('notifications_remise');
    }
}
