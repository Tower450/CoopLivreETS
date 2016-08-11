<?php

use Illuminate\Database\Seeder;

class GestionnaireTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gestionnaires')->insert(array(
            array('email'=>"boss@test.com",'password'=>Hash::make('boss'),'cooperative_id'=>1),
            array('email'=>"boss2@test.com",'password'=>Hash::make('boss2'),'cooperative_id'=>1)
        ));
    }

}
