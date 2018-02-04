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
        //$city = \App\City::get()->random(1)->first();

        $city = \App\City::findOrFail(1);

        if ($city) {

            $data = [];

            $x_limit = 5;
            $y_limit = 5;

            for ($y = 0; $y<$city->max_y + $y_limit; $y++) {
                for ($x = 0; $x<$city->max_x + $x_limit; $x++) {

                    $tile_type = 1;

                    if ($x < $x_limit || ($x > $city->max_x)) {
                        $tile_type = 0;
                    }

                    if ($y < $y_limit || ($y > $city->max_y)) {
                        $tile_type = 0;
                    }

                    $data[] = ['city_id' => $city->id, 'x' => $x, 'y' => $y, 'tile_type' => $tile_type];
                }
            }
        }

        \App\Tile::insert($data);

    }
}
