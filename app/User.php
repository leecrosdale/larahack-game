<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','tile_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tile() {
        return $this->belongsTo('App\Tile');
    }

    public function connection() {
        return $this->hasOne('App\Connection')->where('status',1);
    }

    public function connections() {
        return $this->hasMany('App\Connection');
    }

    public function computers() {
        return $this->hasMany('App\Computer');
    }

    public function beginComputer() {
        return $this->hasOne('App\Computer')->where('begin',1);
    }

}
