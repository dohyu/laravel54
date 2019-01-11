<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirm_code', 'activated'
    ];

    protected $dates = [
        'last_login',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'activated' => 'boolean',
    ];

    public function isAdmin()
    {
        return ($this->id === 12) ? true : false;
    }

    public function scopeSocialUser($query, $email)
    {
        return $query->whereEmail($email)->whereNull('password');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
