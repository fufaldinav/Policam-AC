<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Division extends Model
{
    protected $fillable = [
        'name',
        'organization_id',
        'type'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function persons()
    {
        return $this->belongsToMany('App\Person');
    }
}
