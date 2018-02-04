<?php

namespace App\Http\Controllers;

use App\Computer;
use App\Connection;
use App\Repository\Command;
use App\TerminalLine;
use App\User;
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

    public function getTerminalLines() {

        // Get active connection terminal lines
        $exec = new Command();
        $connection = $exec->getUserConnection(Auth::user());

        $computer = $connection->computer;
        return $computer->lines()->orderBy('id','asc')->get(['message_key', 'message_value', 'created_at'])->sortByDesc('created_at')->take(14);

    }

    public function getPlayer() {

        $user = Auth::user();
        $user->tile;
        $user->tile->location;

        return $user;
    }

    public function command(Request $request) {

        $command = $request->input('command');
        $exec = new Command();
        return $exec->exec($command);

    }


}
