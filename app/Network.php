<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    private $statuses = [
        'closed',
        'open'
    ];

    public function getStatus() {
        return $this->statuses[$this->status];
    }

    public function computers() {
        return $this->belongsToMany('App\Computer');
    }
}
