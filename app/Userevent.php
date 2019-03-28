<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Userevent
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Userevent whereUserId($value)
 */
class Userevent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'description',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'type' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
