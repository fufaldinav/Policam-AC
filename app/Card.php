<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Card
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent /*
 * @property int $id
 * @property string $wiegand
 * @property string $last_conn
 * @property int $controller_id
 * @property int $person_id
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereLastConn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Card whereWiegand($value)
 */
class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wiegand', 'last_conn', 'controller_id', 'person_id'
    ];

    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }
}
