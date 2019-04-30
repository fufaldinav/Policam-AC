<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Division
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $organization_id
 * @property int $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Organization $organization
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Person[] $persons
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereUpdatedAt($value)
 */
class Division extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'organization_id', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'organization', 'persons', //TODO check
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'organization_id' => 'integer',
        'type' => 'integer',
    ];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function persons()
    {
        return $this->belongsToMany('App\Person')->withTimestamps();
    }
}
