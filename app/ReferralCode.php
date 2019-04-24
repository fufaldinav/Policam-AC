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
 */
class ReferralCode extends Model
{
    protected $fillable = [
        'code', 'card', 'user_id'
    ];

    public function users() {
        return $this->belongsToMany('App\User');
    }
}
