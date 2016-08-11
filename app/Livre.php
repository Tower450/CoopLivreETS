<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livre extends Model
{
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = ['price','state'];

    public function descriptionLivre()
    {
        return $this->belongsTo('App\DescriptionLivre','description_livres_id');
    }
    public function etudiant()
    {
        return $this->belongsTo('App\Etudiant');
    }
    public function reservationLivre()
    {
        return $this->hasOne('App\ReservationLivre');
    }
}
