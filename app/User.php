<?php

namespace App;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * App\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Card[] $cards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Controller[] $controllers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Division[] $divisions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Person[] $persons
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Person[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    MustVerifyEmailContract
{
    use Notifiable;

    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'cards',
        'controllers', 'divisions', 'notifications',
        'organizations', 'persons', 'subscriptions',
        'tokens', 'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasRole($role)
    {
        return $this->belongsToMany('App\Role')->get()->contains($role);
    }

    public function isAdmin()
    {
        return $this->hasRole(1);
    }

    public function cards()
    {
        return $this->hasManyDeep('App\Card', ['organization_user', 'App\Organization', 'App\Division', 'division_person', 'App\Person']);
    }

    public function controllers()
    {
        return $this->hasManyDeep('App\Controller', ['organization_user', 'App\Organization']);
    }

    public function divisions()
    {
        return $this->hasManyDeep('App\Division', ['organization_user', 'App\Organization']);
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function organizations()
    {
        return $this->belongsToMany('App\Organization');
    }

    public function persons()
    {
        return $this->hasManyDeep('App\Person', ['organization_user', 'App\Organization', 'App\Division', 'division_person']);
    }

    public function subscriptions()
    {
        return $this->belongsToMany('App\Person');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function tokens()
    {
        return $this->hasMany('App\Token');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }
}
