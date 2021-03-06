<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CitiesTableSeeder::class);
        $this->call(TilesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);

       // $this->call(UsersTableSeeder::class);
    }
}
