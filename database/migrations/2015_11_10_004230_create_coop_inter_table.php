<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoopInterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperatives_externes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('address');
            $table->timestamps();
        });

        Schema::create('livres_a_envoyer', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price');
            $table->integer('state');
            $table->boolean('sended')->default(0);
            $table->integer('description_livres_id')->unsigned();
            $table->foreign('description_livres_id')->references('id')->on('description_livres')->onDelete('cascade');
            $table->integer('cooperatives_externes_id')->unsigned();
            $table->foreign('cooperatives_externes_id')->references('id')->on('cooperatives_externes')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('livres_a_recevoir', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('received')->default(0);
            $table->float('price');
            $table->integer('state');
            $table->integer('description_livres_id')->unsigned();
            $table->foreign('description_livres_id')->references('id')->on('description_livres')->onDelete('cascade');
            $table->integer('cooperatives_externes_id')->unsigned();
            $table->foreign('cooperatives_externes_id')->references('id')->on('cooperatives_externes')->onDelete('cascade');
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
        Schema::drop('livres_a_recevoir');
        Schema::drop('livres_a_envoyer');
        Schema::drop('cooperatives_externes');
    }
}
