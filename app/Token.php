<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Token extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
