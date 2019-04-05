<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\Person
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $f
 * @property string $i
 * @property string|null $o
 * @property int $type
 * @property string $birthday
 * @property string|null $address
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Controller[] $controllers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Division[] $divisions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereUpdatedAt($value)
 */
class Person extends Model
{
    use HasRelationships;

    protected $table = 'persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'f', 'i', 'o', 'type', 'birthday', 'address', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cards', 'controllers', 'divisions', 'photos', 'users',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'integer',
    ];

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function controllers()
    {
        return $this->hasManyDeep(
            'App\Controller',
            ['division_person', 'App\Division', 'App\Organization'],
            [
                'person_id',
                'id',
                'id',
            ],
            [
                'id',
                'division_id',
                'organization_id',
            ]
        );
    }

    public function divisions()
    {
        return $this->belongsToMany('App\Division');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
