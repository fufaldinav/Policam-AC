<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
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
