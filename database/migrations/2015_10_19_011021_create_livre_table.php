<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLivreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('description_livres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ISBN_10',10)->unique()->nullable();
            $table->string('ISBN_13',13)->unique()->nullable();
            $table->string('UPC',13)->unique()->nullable();
            $table->string('title',100);
            $table->string('author',60);
            $table->string('image_link')->nullable();
            $table->integer('pages')->nullable();
            $table->float('price');
            $table->timestamps();
        });

        Schema::create('livres', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price');
            $table->boolean('approved')->default(0);
            $table->boolean('sold')->default(0);
            $table->integer('state');
            $table->integer('etudiant_id')->unsigned();
            $table->foreign('etudiant_id')->references('id')->on('etudiants')->onDelete('cascade');
            $table->integer('description_livres_id')->unsigned();
            $table->foreign('description_livres_id')->references('id')->on('description_livres')->onDelete('cascade');
            $table->timestamps();
        });

        //Add full text search indexes
        DB::statement('ALTER TABLE description_livres ADD FULLTEXT(title, author)');
        DB::statement('CREATE FULLTEXT INDEX title_idx ON description_livres(title)');
        DB::statement('CREATE FULLTEXT INDEX author_idx ON description_livres(author)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('description_livres');
        Schema::drop('livres');
    }
}
