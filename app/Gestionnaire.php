<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Gestionnaire extends Model
{
    protected $hidden = ['created_at','updated_at','id'];
    protected $fillable = ['email', 'password'];

    public function cooperative()
    {
        return $this->belongsTo('App\Cooperative');
    }

    public static function authenticate($data){

        $valid = true;

        //Go find the gestionnaire with email
        if(!empty($data['email'])){
            $gestionnaire = Gestionnaire::where('email',$data['email'])->first();
        }

        //If it finds the data we change the object in array
        if(!empty($gestionnaire)){
            $gestionnaire = $gestionnaire->toArray();
        }
        else{
            $valid = false;
        }

        //If we found something but the password doesn't match, we return an error
        if(!Hash::check($data['password'], $gestionnaire['password'])){
            $valid = false;
        };

        return $valid;
    }
}
