<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Device
 *
 * @property int $id
 * @property int $address
 * @property string $name
 * @property string|null $sn
 * @property string $type
 * @property string|null $fw
 * @property float|null $voltage
 * @property int $alarm
 * @property int $timeout
 * @property int $sd_error
 * @property int $events_queue
 * @property int $cards_count
 * @property int $events_bl
 * @property int $cards_bl
 * @property int $controller_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Controller $controller
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Division[] $divisions
 * @property-read int|null $divisions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read int|null $events_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCardsBl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCardsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereEventsBl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereEventsQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereSdError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Device whereVoltage($value)
 * @mixin \Eloquent
 */
class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'name', 'sn', 'type', 'fw', 'voltage', 'alarm', 'timeout', 'sd_error',
        'events_queue', 'cards_count', 'events_bl', 'cards_bl', 'controller_id',
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
        'sd_error' => 'integer',
        'events_queue' => 'integer',
        'cards_count' => 'integer',
        'events_bl' => 'integer',
        'cards_bl' => 'integer',
        'controller_id' => 'integer',
    ];

    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }

    public function divisions()
    {
        return $this->belongsToMany('App\Division')->withTimestamps();
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
