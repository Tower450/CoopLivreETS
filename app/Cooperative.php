<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    protected $hidden = ['created_at','updated_at','id'];
    protected $fillable = ['address', 'name'];

    public function etudiants()
    {
        return $this->hasMany('Etudiants');
    }

    public function gestionnaires()
    {
        return $this->hasMany('Gestionnaire');
    }
}
