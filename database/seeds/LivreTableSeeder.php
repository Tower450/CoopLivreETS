<?php

use Illuminate\Database\Seeder;

class LivreTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('livres')->insert(array(
            array('price'=>"10.1",'approved'=>0,'sold'=>0,'state'=>0,'etudiant_id'=>1,'description_livres_id'=>2),
            array('price'=>"11.23",'approved'=>0,'sold'=>0,'state'=>1,'etudiant_id'=>1,'description_livres_id'=>3)
        ));
    }

}
