<?php

use Illuminate\Database\Seeder;

class TilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = \App\City::get()->random(1)->first();

        if ($city) {

            $data = [];

            for ($y = 0; $y<$city->max_y; $y++) {
                for ($x = 0; $x<$city->max_x; $x++) {
                    $data[] = ['city_id' => $city->id, 'x' => $x, 'y' => $y, 'tile_type' => 1];
                }
            }
        }

        \App\Tile::insert($data);

    }
}
