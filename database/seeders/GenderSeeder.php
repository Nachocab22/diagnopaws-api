<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gender::create(['name' => 'Hombre']);
        Gender::create(['name' => 'Mujer']);
        Gender::create(['name' => 'No Binario']);
        Gender::create(['name' => 'Género Fluido']);
    }
}
