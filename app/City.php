<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function tiles() {
        return $this->hasMany('App\Tile');
    }
}
