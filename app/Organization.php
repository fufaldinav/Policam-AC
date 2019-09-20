<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Organization
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Controller[] $controllers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Division[] $divisions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Person[] $persons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUpdatedAt($value)
 * @property-read int|null $controllers_count
 * @property-read int|null $divisions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReferralCode[] $referralCodes
 * @property-read int|null $referral_codes_count
 * @property-read int|null $users_count
 */
class Organization extends Model
{
    use HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'controllers', 'divisions', 'persons', 'users',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
    ];

    public function controllers()
    {
        return $this->hasMany('App\Controller');
    }

    public function divisions()
    {
        return $this->hasMany('App\Division');
    }

    public function persons()
    {
        return $this->hasMany('App\Person');
    }

    public function referralCodes()
    {
        return $this->hasMany('App\ReferralCode');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
