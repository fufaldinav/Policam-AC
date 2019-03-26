<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//Авторизация
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
//use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
//Модель
use Illuminate\Database\Eloquent\Model;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable;

    use Authenticatable, Authorizable, CanResetPassword;

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
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->hasMany('App\Token');
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
        return $this->belongsToMany('App\Person');
    }
}
