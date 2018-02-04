<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    public function computers() {
        return $this->belongsToMany('App\Computer');
    }

    public function computerSoftware() {
        return $this->hasMany('App\ComputerSoftware');
    }
}
