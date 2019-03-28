<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Controller
 *
 * @mixin \Eloquent
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
 * @property string|null $last_conn
 * @property int $organization_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereConnFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereFw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereLastConn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controller whereUpdatedAt($value)
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

    protected $casts = [
        'mode' => 'integer',
        'active' => 'integer',
        'online' => 'integer',
        'last_conn' => 'datetime',
        'organization_id' => 'integer',
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
