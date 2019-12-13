<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Device
 *
 * @property-read \App\Controller $controller
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $address
 * @property string $name
 * @property string|null $sn
 * @property string $type
 * @property string|null $fw
 * @property int|null $voltage
 * @property int $alarm
 * @property int $timeout
 * @property \Illuminate\Support\Carbon $sd_error
 * @property int $controller_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereSdError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereVoltage($value)
 */
class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'sn', 'type', 'fw', 'voltage', 'alarm', 'timeout', 'sd_error', 'controller_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'integer',
        'voltage' => 'float',
        'alarm' => 'integer',
        'timeout' => 'integer',
        'sd_error' => 'datetime',
        'controller_id' => 'integer',
    ];

    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }
}
