<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 21:02
 */

namespace App\Repository;


use App\Software;

class Game
{

    public function run() {

        $software = Software::where('name', 'botnet')->first();
        // and installed botnet software. Estimated cash: £' . ($connectedComputer->gpu * 10 . " per hour"

        foreach ($software->computerSoftware as $cs) {
            $user = $cs->user;
            $computer = $cs->computer;
            $user->cash = $user->cash + ($computer->gpu * 10);
            $user->save();
        }
    }

}