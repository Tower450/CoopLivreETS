<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('CoopTableSeeder');
        $this->call('EtudiantTableSeeder');
        $this->call('DescriptionLivreTableSeeder');
        $this->call('LivreTableSeeder');
        $this->call('GestionnaireTableSeeder');

        Model::reguard();
    }
}
