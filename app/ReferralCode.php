<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReferralCode
 *
 * @property int $id
 * @property string $code
 * @property string $card
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereUserId($value)
 * @mixin \Eloquent
 * @property int $organization_id
 * @property int $activated
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Person[] $persons
 * @property-read int|null $persons_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereOrganizationId($value)
 * @property string|null $sl0
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereSl0($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReferralCode whereType($value)
 */
class ReferralCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'card', 'sl0', 'type', 'user_id', 'organization_id', 'activated',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'card',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
        'activated' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function person()
    {
        return $this->hasOne('App\Person');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
