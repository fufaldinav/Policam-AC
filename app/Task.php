<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Eloquent
 */
class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'controller_id', 'json'
    ];

    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }
}
