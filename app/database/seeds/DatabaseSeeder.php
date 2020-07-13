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
        \Illuminate\Database\Eloquent\Model::unguard();

        $this->call([
            UsersSeeder::class,
            EventsSeeder::class,
            CitySeeder::class
        ]);

        \Illuminate\Database\Eloquent\Model::reguard();
    }
}
