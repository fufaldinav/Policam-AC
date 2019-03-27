<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Photo extends Model
{
    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
