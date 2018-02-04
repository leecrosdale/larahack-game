<?php
/**
 * Created by PhpStorm.
 * User: accou
 * Date: 04/02/2018
 * Time: 15:39
 */

namespace App\Repository;

use App\Computer;
use App\Lockout;
use App\TerminalLine;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Command
{
    private $commandList = [
        '/connect', // TODO
        '/disconnect', // TODO
        '/help',
        '/hello', // TODO
        '/scan',
        '/attack'
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

    protected function networkOrIP() {
        return substr_count($this->command[1],'.');
    }

    protected function status($status_message = "An error occurred", $status = ">") {
        return ['status' => $status, 'status_text' => $status_message];
    }

    protected function doAttack($device, $computer) {

        // Check that computer is not locked out from device.
        $lockout = Lockout::where('device_id', $device->id)->where('device_type', get_class($device))->where('computer_id', $computer->id)->where('updated_at', '>=', Carbon::now()->addMinutes(-5)->toDateTimeString())->first();

        if ($lockout) {

            $time = Carbon::parse($lockout->updated_at)->addMinutes(5);

            if ($lockout->attempts > 2) {
                return 'You are currently locked out of this system/network. You can try again in ' . $time->diffForHumans();
            } else {
                $lockout->attempts = $lockout->attempts + 1;
                $lockout->save();
            }
        } else {

            Lockout::create([
                'computer_id' => $computer->id,
                'device_id' => $device->id,
                'device_type' => get_class($device)
            ]);

        }

        $damage = ($computer->gpu + $computer->ram) + $computer->hdd * $computer->cpu;

        $device->health = $device->health - $damage;

        if ($device->health <= 0 && $device->security > 0) {
            $device->security = $device->security -1;
            $device->health = $device->max_health;
        }

        $device->save();

        if ($device->security == 0) {
            return 'Security disabled!';
        } else {
            return 'Security Level: ' . $device->security . " - Firewall Status: " . $device->health . "/" . $device->max_health;
        }

    }

    // TERMINAL COMMANDS DO NOT ADD NON COMMANDS BELOW HERE -------------------------------

    private function help() {
        return $this->status( "Available Commands: " . implode(", ", $this->commandList));
    }

    private function connect() {

        $count = $this->networkOrIP($this->command[1]);

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
                        return $this->status('Connection to ' . $network->name . ' has been established, search for computers by typing /scan computers');

                    } else {
                        // User can't connect, and needs to attack the network to bring down security
                        return $this->status("You can't connect while security is up. Current security level: " . $network->security);
                    }
                }
            }
        }

        return $this->status('Network or IP Address not found, check that your computer is in range.');

    }

    private function attack() {

        if ($this->networkOrIP($this->command[1]) > 0) {

            // IP
            // Make sure that IP is still connected to a network that you are connected to in range.

            $ip = $this->command[1];
            $computer = Computer::where('ip', $ip)->first();

            $status_text = 'Unable to attack. Check that your computer is in range of the network';

            $networks = $this->getNetworks();
            if ($networks->isEmpty() || $computer == null) {
                $status_text = 'Computer not found, or you are no longer connected to a network in range';
            } else {

                foreach ($networks as $network) {
                    $connectedComputer = $network->computers()->where('computer_id', '!=', $computer->id)->first();

                    if ($connectedComputer) {
                        return $this->status($this->doAttack($connectedComputer, $this->connection->computer));
                    }

                }

            }

            return $this->status($status_text);

        } else {

            // Network
            $network = $this->getNetworks()->where('name', $this->command[1])->first();

            if ($network) {

                // Check the user is not connected.
                $connected = $network->computers()->where('computer_id', $this->connection->computer->id)->first() != null;

                if ($connected) {
                    return $this->status("You can't attack a network you are connected to.");
                }

                $status = $this->doAttack($network, $this->connection->computer);

                return $this->status($status);

            }

        }

        return $this->status('Unable to attack. Check that your computer is in range of the network');

    }

    private function scan() {

        $status_text = "type '/scan networks' or '/scan computers'";

        $networks = $this->getNetworks();
        if ($networks->isEmpty()) {
            $status_text = 'No Networks found';
        } else {

            switch ($this->command[1]) {

                case "networks":

                    $status_text = 'Networks found: ';

                    foreach ($networks as $network) {
                        $status_text .= $network->name . " - Security Level: " . $network->security . ", ";
                    }

                    $status_text = rtrim($status_text, ", ");

                    break;

                case "computers":

                    $status_text = '';

                    // Find computers connected to networks
                    foreach ($networks as $network) {

                        $computers = $network->computers()->where('computer_id', '!=', $this->connection->computer->id)->get();

                        if (!$computers->isEmpty()) {
                            $status_text = "Network: " . $network->name . " ( ";
                            foreach ($computers as $computer) {
                                $status_text .= "[" . $computer->ip . " - Security Level: " . $computer->security . "], ";
                            }
                            $status_text = rtrim($status_text, ", ");
                            $status_text .= " ), ";
                        }
                    }

                    $status_text = rtrim($status_text, ", ");

                    if ($status_text == '') {
                        $status_text = 'No Computers found on any connected networks';
                    }

                break;

            }

        }

        return $this->status($status_text);

    }

}