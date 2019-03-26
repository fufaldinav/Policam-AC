<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model
{
    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
