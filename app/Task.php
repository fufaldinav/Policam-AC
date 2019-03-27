<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Task extends Model
{
    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }
}
