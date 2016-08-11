<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DescriptionLivre extends Model
{
    protected $hidden = ['created_at','updated_at','id'];
    protected $fillable = ['ISBN_10','ISBN_13', 'title','author','image_link','pages','price'];
    protected $table = 'description_livres';

    public function livres()
    {
        return $this->hasMany('App\Livre');
    }

}
