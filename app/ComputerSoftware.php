<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComputerSoftware extends Model
{
    protected $fillable = ['computer_id', 'software_id', 'user_id'];

    protected $table = 'computer_software';

    public function computer() {
        return $this->belongsTo('App\Computer');
    }

    public function software() {
        return $this->belongsTo('App\Software');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
