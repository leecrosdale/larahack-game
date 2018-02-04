<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = \App\City::findOrFail(2);

        $tiles = $city->tiles()->where('tile_type', '!=', 0)->inRandomOrder()->take(300)->get();

        $types = ['house', 'shop'];

        // Types house, shop
        foreach ($tiles as $tile) {

            $location_type = rand(0,1);

            $location = \App\Location::create([
                'tile_id' => $tile->id,
                'location_type' => $location_type,
                'name' => $types[$location_type] . "_" . $tile->id
            ]);

            $security = rand(1,5);
            $health = $security * 100;

            $network = \App\Network::create([
                'tile_id' => $tile->id,
                'name' => 'network_' . $tile->id,
                'max_health' => $health,
                'health' => $health,
                'security' => $security,
                'status' => 1
            ]);


        }
    }
}
