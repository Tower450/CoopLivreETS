<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Etudiant extends Model
{
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = ['email', 'password','phone','name'];

    public function cooperative()
    {
        return $this->belongsTo('App\Cooperative');
    }

    public function livres()
    {
        return $this->hasMany('App\Livre');
    }

    public static function authenticate($data){
        $valid = true;

        //Go find the student with email or phone
        if(empty($data['email'])){
            $student = Etudiant::where('phone',$data['phone'])->first();
        }
        else{
            $student = Etudiant::where('email',$data['email'])->first();
        }

        //If it finds the data we change the object in array
        if(!empty($student)){
            $student = $student->toArray();
        }
        else{
            $valid = false;
        }

        //If we found something but the password doesn't match, we return an error
        if(!Hash::check($data['password'], $student['password'])){
            $valid = false;
        };

        return $valid;
    }
}
