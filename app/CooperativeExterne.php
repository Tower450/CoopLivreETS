<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CooperativeExterne extends Model
{
    protected $table = 'cooperatives_externes';
    protected $fillable = ['name','address'];

}
