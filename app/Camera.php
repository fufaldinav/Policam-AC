<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Camera
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent /*
 * @property int $id
 * @property string $name
 * @property int $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CameraSnapshot[] $snapshots
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Controller[] $controllers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUpdatedAt($value)
 */
class Camera extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'controllers', 'snapshots',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
    ];

    public function snapshots()
    {
        return $this->HasMany('App\CameraSnapshot');
    }

    public function controllers()
    {
        return $this->belongsToMany('App\Controller')->withTimestamps();
    }
}
