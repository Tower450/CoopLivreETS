<?php

use Illuminate\Database\Seeder;

class CoopTableSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cooperatives')->insert(array(
            array('name'=>"ohouais",'address'=>"568 rue magique")
        ));
    }
}