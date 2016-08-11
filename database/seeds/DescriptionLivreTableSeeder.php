<?php

use Illuminate\Database\Seeder;
use App\DescriptionLivre;

class DescriptionLivreTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert = [
            [
                'image_link'=>"url",
                'title'=>"Le livre",
                'ISBN_13'=>"9781408834824",
                'ISBN_10'=>"9781408834824",
                'UPC'=>"9781408834824",
                'price'=>25.25,
                'author'=>"power",
                'pages'=>200
            ],
            [
                'image_link'=>"http://ecx.images-amazon.com/images/I/51j99lcdidL._SL160_.jpg",
                'title'=>"Comment faire l'amour avec un Nègre sans se fatiguer",
                'ISBN_13'=>"9782892953206",
                'ISBN_10'=>"2892953200",
                'UPC'=>"9782892953206",
                'price'=>13.46,
                'author'=>"Dany Laferrière",
                'pages'=>192
            ],
            [
                'image_link'=>"http://ecx.images-amazon.com/images/I/51RK6EATVPL._SL160_.jpg",
                'title'=>"Le goût des jeunes filles",
                'ISBN_13'=>"9782890058828",
                'ISBN_10'=>"2890058824",
                'UPC'=>"9782890058828",
                'price'=>22.46,
                'author'=>"Dany Laferrière",
                'pages'=>336
            ]
        ];

        foreach($insert as $row ){
            $Livre = new DescriptionLivre();
            $Livre->fill($row);
            $Livre->save();
        }
    }

}
