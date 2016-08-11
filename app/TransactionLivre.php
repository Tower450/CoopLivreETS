<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionLivre extends Model
{
    protected $hidden = ['updated_at','id'];
    protected $fillable = ['refNumber','amount', 'refunded'];
    protected $table = 'transaction_livres';

    public function reservationLivre()
    {
        return $this->hasOne('App\ReservationLivre','transaction_livres_id');
    }
}
