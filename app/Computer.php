<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Computer extends Model
{
    protected $fillable = ['user_id', 'ip', 'ram', 'cpu', 'hdd', 'gpu', 'online', 'begin', 'active', 'tile_id','security','max_health','health'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function lines() {
        return $this->hasMany('App\TerminalLine')->where('user_id', Auth::user()->id);
    }

    public function connections() {
        return $this->hasMany('App\Connection');
    }

    public function connection() {
        return $this->hasOne('App\Connection')->where('user_id', Auth::user()->id);
    }

    public function tile() {
        return $this->belongsTo('App\Tile');
    }

    public function networks() {
        return $this->belongsToMany('App\Network');
    }

    public function software() {
        return $this->belongsToMany('App\Software');
    }

}
