<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Organization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'type'
    ];

    public function divisions()
    {
        return $this->hasMany('App\Division');
    }

    public function controllers()
    {
        return $this->hasMany('App\Controller');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
