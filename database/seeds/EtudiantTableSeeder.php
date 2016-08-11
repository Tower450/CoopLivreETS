<?php

use Illuminate\Database\Seeder;

class EtudiantTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('etudiants')->insert(array(
            array('email'=>"test1@hotmail.com",'password'=>Hash::make('test1'),'cooperative_id'=>1,'name'=>'Jonathan'),
            array('email'=>"test2@hotmail.com",'password'=>Hash::make('test2'),'cooperative_id'=>1,'name'=>'Jonathan'),
            array('email'=>"test3@hotmail.com",'password'=>Hash::make('test3'),'cooperative_id'=>1,'name'=>'Jonathan'),
            array('email'=>"test4@hotmail.com",'password'=>Hash::make('test4'),'cooperative_id'=>1,'name'=>'Jonathan'),
            array('email'=>"test5@hotmail.com",'password'=>Hash::make('test5'),'cooperative_id'=>1,'name'=>'Jonathan')
        ));
    }

}
