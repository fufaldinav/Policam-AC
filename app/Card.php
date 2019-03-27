<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wiegand', 'last_conn', 'controller_id', 'person_id'
    ];

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
