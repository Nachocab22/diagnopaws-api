<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            SpeciesSeeder::class,
            BreedSeeder::class,
            VaccineSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
