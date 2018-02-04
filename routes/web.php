<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {

    Route::get('map/{city_id}/data', 'MapController@getMapData' ); // Load the map
    Route::get('player/move/{direction}', 'UserController@move'); // Move the player
    Route::get('player/terminal/lines', 'UserController@getTerminalLines');
    Route::post('player/command', 'UserController@command'); // Command
    Route::get('player', 'UserController@getPlayer');

    Route::get('test', function() {

        $game = new \App\Repository\Command();





        return $game->getPrices($game->getUpgrades(), \App\Computer::find(1)->first());




    });

});