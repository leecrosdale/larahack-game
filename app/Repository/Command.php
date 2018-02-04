<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 15:39
 */

namespace App\Repository;

use App\Network;
use App\TerminalLine;
use App\User;
use Illuminate\Support\Facades\Auth;

class Command
{
    private $commandList = [
        '/connect', // TODO
        '/disconnect', // TODO
        '/help',
        '/hello', // TODO
        '/scan',
    ];

    private $command;
    private $connection;
//    private $computer;

    public function exec($command) {

        // Explode command

        $line = $this->status("Invalid Command");

        $command = explode(' ', $command);

        $this->connection = $this->getUserConnection(Auth::user());

        if (in_array($command[0], $this->commandList)) {
            $this->command = $command;
            $proc = substr($command[0],1, strlen($command[0]));
            $line = $this->{$proc}();
        }


        TerminalLine::create([
            'user_id' => Auth::user()->id,
            'computer_id' => $this->connection->computer->id,
            'message_key' => $line['status'],
            'message_value' => $line['status_text']
        ]);


        return response()->json(['ok']);

    }

    public function getUserConnection(User $user) {

        $connection = $user->connection;

        if ($connection == null) {
            // Connect to users OWN computer.
            $connection =$user->activeComputer->connection;

            if (!$connection) {
                $connection = \App\Helpers\Connection::createConnection($user, $user->activeComputer);
            } else {
                $connection->status = 1;
                $connection->save();
            }
        }

        return $connection;
    }


    protected function isOwned() {
        return $this->connection->computer->user_id == Auth::user()->id && $this->connection->computer->active == 1;
    }
    protected function getNetworks() {

        if ($this->isOwned()) {
            $networks = Auth::user()->tile->networks;
        } else {
            $networks = $this->connection->computer->tile->networks;
        }

        return $networks;

    }

    protected function status($status_message = "An error occurred", $status = ">") {
        return ['status' => $status, 'status_text' => $status_message];
    }


    // TERMINAL COMMANDS DO NOT ADD NON COMMANDS BELOW HERE -------------------------------

    private function help() {
        return $this->status( "Available Commands: " . implode(", ", $this->commandList));
    }

    private function connect() {

        $count = substr_count($this->command[1],'.');

        if ($count > 0) {
            // Connecting to IP
            // TODO Connect to IP

        } else {
            // Connecting to network
            $network = $this->getNetworks()->where('name', $this->command[1])->first();

            if ($network) {
                // Check if user is already connected
                $checkConnected = $this->connection->computer->networks()->where('network_id', $network->id)->first();

                if ($checkConnected) {
                    return $this->status('You are already connected to ' . $checkConnected->name);
                } else {

                    if ($network->security == 0) {
                        // User can connect, network is open
                        // Add the user to this network

                        $network->computers()->attach($this->connection->computer->id);
                        return $this->status('Connection to ' . $network->name . ' has been established, search for computers by typing /scan players');

                    } else {
                        // User can't connect, and needs to attack the network to bring down security
                        return $this->status("You can't connect while security is up. Current security level: " . $network->security);
                    }


                }
            }
        }

        return $this->status('Network or IP Address not found, make sure you are in range.');

    }

    private function scan() {

        $owned = $this->isOwned();

        $status_text = "type '/scan networks' or '/scan players'";

        switch ($this->command[1]) {

            case "networks":

                $networks = $this->getNetworks();

                if ($networks->isEmpty()) {
                    $status_text = 'No Networks found';
                } else {

                    $status_text = 'Networks found: ';

                    foreach ($networks as $network) {
                        $status_text .= $network->name . " - " . $network->getStatus() . ", ";
                    }

                    $status_text = rtrim($status_text,", ");
                }

                break;

            case "players":

                // TODO do this
                $status_text = "You can't do this yet :(";

                break;

        }

        return $this->status($status_text);

    }

}