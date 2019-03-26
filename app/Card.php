<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
