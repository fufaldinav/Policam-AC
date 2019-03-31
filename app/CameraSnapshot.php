<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CameraSnapshot
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent /*
 * @property int $id
 * @property string $name
 * @property int $type
 * @property-read \App\Camera $camera
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCameraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUpdatedAt($value)
 */
class CameraSnapshot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'camera_id', 'time', 'path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'camera',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'camera_id' => 'integer',
    ];

    public function camera()
    {
        return $this->belongsTo('App\Camera');
    }
}
