<?php

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\City::create([
            'name' => 'Lara City',
            'max_x' => 50,
            'max_y' => 50
        ]);
    }
}
