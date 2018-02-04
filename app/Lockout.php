<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lockout extends Model
{
    protected $fillable = ['computer_id', 'device_id', 'device_type'];
}
