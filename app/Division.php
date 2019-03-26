<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function persons()
    {
        return $this->belongsToMany('App\Person');
    }
}
