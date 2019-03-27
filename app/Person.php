<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Person extends Model
{
    protected $table = 'persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f', 'i', 'o', 'type', 'birthday', 'address', 'phone'
    ];

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function divisions()
    {
        return $this->belongsToMany('App\Division');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
