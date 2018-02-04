<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TerminalLine extends Model
{

    protected $fillable = ['computer_id', 'user_id', 'message_key', 'message_value'];

    public function computer() {
        return $this->belongsTo('App\Computer');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

}
