<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationLivre extends Model
{
    protected $hidden = ['updated_at','id'];
    protected $fillable = ['isPicked'];
    protected $table = 'reservation_livres';

    public function transactionLivre()
    {
        return $this->belongsTo('App\TransactionLivre','transaction_livres_id');
    }
    public function livre()
    {
        return $this->belongsTo('App\Livre','livres_id');
    }
}
