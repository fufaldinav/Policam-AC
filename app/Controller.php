<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Controller
 *
 * @property int $id
 * @property string $name
 * @property string $sn
 * @property string $type
 * @property string|null $fw
 * @property string|null $conn_fw
 * @property int $mode
 * @property string|null $ip
 * @property int $active
 * @property int $online
 * @property \Illuminate\Support\Carbon|null $last_conn
 * @property int $organization_id
 * @property int $alarm
 * @property int $sd_error
 * @property int $events_queue
 * @property int $messages_queue
 * @property int $events_bl
 * @property int $messages_bl
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Camera[] $cameras
 * @property-read int|null $cameras_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Device[] $devices
 * @property-read int|null $devices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereAlarm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereConnFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereEventsBl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereEventsQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereLastConn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereMessagesBl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereMessagesQueue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereSdError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Controller extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mode' => 'integer',
        'active' => 'integer',
        'online' => 'integer',
        'last_conn' => 'datetime',
        'organization_id' => 'integer',
        'alarm' => 'integer',
        'sd_error' => 'integer',
        'events_queue' => 'integer',
        'messages_queue' => 'integer',
        'events_bl' => 'integer',
        'messages_bl' => 'integer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sn', 'type', 'fw', 'conn_fw', 'mode', 'ip', 'active', 'online', 'last_conn', 'organization_id',
        'alarm', 'sd_error', 'events_queue', 'messages_queue', 'events_bl', 'messages_bl',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cameras', 'notifications', 'organization', 'tasks',
    ];

    public function cameras()
    {
        return $this->belongsToMany('App\Camera')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function devices()
    {
        return $this->hasMany('App\Device');
    }
}
