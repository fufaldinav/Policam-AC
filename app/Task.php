<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }
}
