<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Controller extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sn', 'type', 'fw', 'conn_fw', 'mode', 'ip', 'active', 'online', 'last_conn', 'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
