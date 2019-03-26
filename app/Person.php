<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function divisions()
    {
        return $this->belongsToMany('App\Division');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
