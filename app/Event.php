<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'controller_id', 'event', 'flag', 'time', 'card_id'
    ];

    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }

    public function card()
    {
        return $this->belongsTo('App\Card');
    }
}
