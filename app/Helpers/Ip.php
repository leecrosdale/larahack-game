<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 23:19
 */

namespace App\Helpers;


class Ip
{

    public static function generateIP() {
        return mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
    }

}