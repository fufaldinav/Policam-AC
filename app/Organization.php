<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 */
class Organization extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'type'
    ];

    public function divisions()
    {
        return $this->hasMany('App\Division');
    }

    public function controllers()
    {
        return $this->hasMany('App\Controller');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
