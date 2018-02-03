<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tile extends Model
{

    protected $fillable = ['city_id', 'x', 'y', 'tile_type'];

    public function city() {
        return $this->belongsTo('App\City');
    }

    public function location() {
        return $this->hasOne('App\Location');
    }

    public function users() {
        return $this->hasMany('App\User');
    }

}
