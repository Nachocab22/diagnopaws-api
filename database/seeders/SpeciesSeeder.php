<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Species::create(['name' => 'Perro']);
        Species::create(['name' => 'Gato']);
        Species::create(['name' => 'Conejo']);
        Species::create(['name' => 'Pájaro']);
        Species::create(['name' => 'Reptil']);
        Species::create(['name' => 'Roedor']);
        Species::create(['name' => 'Pez']);
        Species::create(['name' => 'Caballo']);
        Species::create(['name' => 'Otro']);
    }
}
