<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 15:09
 */

namespace App\Helpers;


use App\Computer;
use App\User;

class Connection
{

    public static function createConnection(User $user, Computer $computer) {

        $connection = \App\Connection::create([
            'user_id' => $user->id,
            'computer_id' => $computer->id,
            'status' => 1
        ]);

        return $connection;

    }

}