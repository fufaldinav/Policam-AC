<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Userevent extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
