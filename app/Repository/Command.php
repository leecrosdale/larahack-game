<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 15:39
 */

namespace App\Repository;

use App\TerminalLine;
use App\User;
use Illuminate\Support\Facades\Auth;

class Command
{
    private $commandList = [
        '/connect',
        '/disconnect',
        '/help',
        '/hello'
    ];


    public function exec($command) {

        // Explode command

        $line = ['status' => ">", 'status_text' => "Invalid Command"];

        $command = explode(' ', $command);

        if (in_array($command[0], $this->commandList)) {
            $proc = substr($command[0],1, strlen($command[0]));
            $line = $this->{$proc}();
        }

        $connection = $this->getUserConnection(Auth::user());
        TerminalLine::create([
            'user_id' => Auth::user()->id,
            'computer_id' => $connection->computer->id,
            'message_key' => $line['status'],
            'message_value' => $line['status_text']
        ]);

        return response()->json(['ok']);

    }

    public function getUserConnection(User $user) {

        $connection = $user->connection;

        if ($connection == null) {
            // Connect to users OWN computer.
            $connection =$user->beginComputer->connection;

            if (!$connection) {
                $connection = \App\Helpers\Connection::createConnection($user, $user->beginComputer);
            } else {
                $connection->status = 1;
                $connection->save();
            }
        }

        return $connection;
    }

    private function help() {
        return ['status' => ">", 'status_text' => "Available Commands: " . implode(", ", $this->commandList)];
    }

}