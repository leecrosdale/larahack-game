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

    public function north() {
        return Tile::where('y', $this->y - 1)->where('x', $this->x)->where('city_id', $this->city_id)->where('tile_type', '!=', 0);
    }

    public function south() {
        return Tile::where('y', $this->y + 1)->where('x', $this->x)->where('city_id', $this->city_id)->where('tile_type', '!=', 0);
    }

    public function west() {
        return Tile::where('x', $this->x - 1)->where('y', $this->y)->where('city_id', $this->city_id)->where('tile_type', '!=', 0);
    }

    public function east() {
        return Tile::where('x', $this->x + 1)->where('y', $this->y)->where('city_id', $this->city_id)->where('tile_type', '!=', 0);
    }

}
