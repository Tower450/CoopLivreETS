<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_livres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refNumber');
            $table->float('amount');
            $table->boolean('refunded')->default(0);
            $table->timestamps();
        });

        Schema::create('reservation_livres', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('isPicked')->default(0);
            $table->integer('transaction_livres_id')->unsigned();
            $table->foreign('transaction_livres_id')->references('id')->on('transaction_livres')->onDelete('cascade');
            $table->integer('livres_id')->unsigned();
            $table->foreign('livres_id')->references('id')->on('livres')->onDelete('cascade');
            $table->integer('etudiants_id')->unsigned();
            $table->foreign('etudiants_id')->references('id')->on('etudiants')->onDelete('cascade');
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
        Schema::drop('transaction_livres');
        Schema::drop('reservation_livres');
    }
}
