<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 03/02/2018
 * Time: 17:02
 */

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class Map
{


    public static function generateData(\App\City $city) {

        $user_tile = Auth::user()->tile;

        $max_x = 6;
        $max_y = 3;

        $tiles = $city->tiles()->where('x' ,'>' , $user_tile->x - $max_x)
            ->where('x' ,'<' , $user_tile->x + $max_x)
            ->where('y' ,'>' , $user_tile->y - $max_y)
            ->where('y' ,'<' , $user_tile->y + $max_y)
            ->get();
        $data = [];

        $backgroundcolors = [
            'black', 'green'
        ];

        foreach ($tiles as $tile) {

            if (!$tile->users()->get()->isEmpty()) {
                $background = 'red';
            } else {

                $background = $backgroundcolors[$tile->tile_type];

            }

            $data[$tile->y][$tile->x] = ['tile' => $tile, 'location' => $tile->location, 'players' => $tile->users, 'background' => ['color' => $background]];
        }

        return $data;

    }
}