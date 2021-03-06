<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
        //Irá esconder password, email e name
        'password', 'remember_token', 'name', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Todos os nomes serão convertidos para boolean ()
        //'name' => 'name'
    ];

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function orders()
    {
        //Este usuário tem muitos (hasMany) orders
        return $this->hasMany(UserOrder::class);
    }
}
