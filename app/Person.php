<?php

namespace App;

use App\Policam\Ac\Tasker;
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
 * @property int|null $gender
 * @property int|null $referral_code_id
 * @property-read int|null $cards_count
 * @property-read int|null $divisions_count
 * @property-read int|null $photos_count
 * @property-read \App\ReferralCode|null $referralCode
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Person whereReferralCodeId($value)
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
        'f', 'i', 'o', 'gender', 'type', 'birthday', 'address', 'phone', 'organization_id', 'referral_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'cards', 'controllers', 'divisions', 'photos', 'users', //TODO check
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
        return $this->belongsToMany('App\Division')->withTimestamps();
    }

    public function organizations()
    {
        return $this->hasManyDeep(
            'App\Organization',
            ['division_person', 'App\Division'],
            ['person_id', 'id', 'id'],
            ['id', 'division_id', 'organization_id']
        );
    }

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }

    public function referralCode()
    {
        return $this->belongsTo('App\ReferralCode');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function moveToDivision(int $divisionId): Division
    {
        $div = Division::find($divisionId);

        if ($this->divisions->contains($div)) {
            return $div;
        }


        $oldDiv = $div->organization->divisions->first(function ($division) {
            return $division->persons->contains($this);
        });

        $this->divisions()->attach($div->id);
        if (isset($oldDiv)) {
            $this->divisions()->detach($oldDiv->id);
        }

        return $div;
    }

    public function detachAllDivisions(): self
    {
        $this->divisions()->detach();

        return $this;
    }

    public function attachCard(string $card, int $organizationId): self
    {
        $tasker = new Tasker();

        $card = Card::firstOrCreate(['wiegand' => $card]);

        $this->cards()->save($card);

        $organization = Organization::find($organizationId);

        foreach ($organization->controllers as $ctrl) {
            $tasker->addCards($ctrl->type, [$card->wiegand]);
            $tasker->add($ctrl->id);
        }

        $tasker->send();

        return $this;
    }

    public function detachCard(string $card, int $organizationId): self
    {
        $tasker = new Tasker();

        $card = Card::where(['wiegand' => $card])->first();

        if (! $card) {
            return $this;
        }

        $card->person_id = 0;
        $card->save();

        $organization = Organization::find($organizationId);

        foreach ($organization->controllers as $ctrl) {
            $tasker->delCards($ctrl->type, [$card->wiegand]);
            $tasker->add($ctrl->id);
        }

        $tasker->send();

        return $this;
    }

    public function detachAllCards(): self
    {
        $tasker = new Tasker();

        foreach ($this->cards as $card) {
            $card->person_id = 0;
            $card->save();

            foreach ($this->controllers as $ctrl) {
                $tasker->delCards($ctrl->type, [$card->wiegand]);
                $tasker->add($ctrl->id);
            }
        }

        $tasker->send();

        return $this;
    }

    public function attachPhotos(array $photos): self
    {
        foreach ($photos as $photo) {
            $photo = Photo::find($photo['id']);

            if (! $photo) {
                continue;
            }

            $this->photos()->save($photo);
        }

        return $this;
    }

    public function detachPhotos(array $photos): self
    {
        foreach ($photos as $photo) {
            $photo = Photo::find($photo['id']);

            if (! $photo) {
                continue;
            }

            $photo->delete(); //TODO удаление файла
        }

        return $this;
    }

    public function detachAllPhotos(): self
    {
        foreach ($this->photos as $photo) {
            $photo->delete(); //TODO удаление файла
        }

        return $this;
    }

    public function attachSubscribers(array $subs): self
    {
        foreach ($subs as $user_id) {
            $user = User::find($user_id);

            if (! $user) {
                continue;
            }

            $this->users()->syncWithoutDetaching($user->id);
        }

        return $this;
    }

    public function detachAllSubscribers(): self
    {
        foreach ($this->users as $sub) {
            $this->users()->detach($sub->id);
        }

        return $this;
    }

    public function attachOrganizations(array $organizations): self
    {
        if ($organizations['basic'] !== null) {
            $division = Division::where(['organization_id' => $organizations['basic'], 'type' => 0])->first();
            $this->divisions()->syncWithoutDetaching($division->id);
        }

        $divisionsToAttach = [];
        foreach ($organizations['additional'] as $orgId) {
            $division = Division::where(['organization_id' => $orgId, 'type' => 0])->first();
            $divisionsToAttach[] = $division->id;
        }
        $this->divisions()->syncWithoutDetaching($divisionsToAttach);

        return $this;
    }
}
