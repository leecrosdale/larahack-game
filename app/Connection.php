<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = ['computer_id', 'user_id', 'status'];

    public function computer() {
        return $this->belongsTo('App\Computer');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
