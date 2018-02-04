<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tile = \App\Tile::get()->random(1)->first();

        if ($tile) {

            \App\User::create([
                'name' => 'HackerMan',
                'email' => 'lee@lee2.com',
                'password' => \Illuminate\Support\Facades\Hash::make('secret'),
                'tile_id' => $tile->id,
            ]);

        }

    }
}
