<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }

    public function card()
    {
        return $this->belongsTo('App\Card');
    }
}
