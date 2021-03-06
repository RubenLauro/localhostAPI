<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'local', 'avatar', 'messaging_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function favorites()
    {
        return $this->belongsToMany('App\Place', 'favorites', 'user_id', 'place_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function linkedSocialAccounts()
    {
        return $this->hasMany('App\LinkedSocialAccount');
    }
}
