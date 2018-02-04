<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function move($direction) {

        $tile = Auth::user()->tile;

        if ($tile) {
            $newTile = $tile->{$direction}()->first();
            if ($newTile) {

                $user = Auth::user();
                $user->tile_id = $newTile->id;
                $user->save();

                return response()->json(['ok']);

            } else {
                return response()->json(['failed']);
            }
        }
        return response()->json(['failed']);
    }
}
