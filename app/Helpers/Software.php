<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 20:43
 */

namespace App\Helpers;

use App\Computer;
use App\ComputerSoftware;
use App\User;
use Illuminate\Support\Facades\Auth;

class Software
{

    public static function installSoftware(\App\Software $software, Computer $computer, User $user) {

        $computerSoftware = $user->computerSoftware()->where('software_id', $software->id)->where('computer_id', $computer->id)->first();

        if ($computerSoftware) return false;

        ComputerSoftware::create([
            'software_id' => $software->id,
            'computer_id' => $computer->id,
            'user_id' => $user->id
        ]);

        return true;

    }

}