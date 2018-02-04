<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComputerNetwork extends Model
{
    public function computer() {
        return $this->hasOne('App\Computer');
    }

    public function network() {
        return $this->hasOne('App\Network');
    }


}
