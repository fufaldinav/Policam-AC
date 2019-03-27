<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Notification extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
