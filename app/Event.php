<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Event
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $controller_id
 * @property int $event
 * @property int $flag
 * @property string $time
 * @property int $card_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Card $card
 * @property-read \App\Controller $controller
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereControllerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereUpdatedAt($value)
 */
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'controller_id', 'event', 'flag', 'time', 'card_id'
    ];

    protected $casts = [
        'controller_id' => 'integer',
        'event' => 'integer',
        'flag' => 'integer',
        'time' => 'datetime',
        'card_id' => 'integer',
    ];

    public function controller()
    {
        return $this->belongsTo('App\Controller');
    }

    public function card()
    {
        return $this->belongsTo('App\Card');
    }
}
