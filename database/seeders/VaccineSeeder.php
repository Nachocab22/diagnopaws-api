<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vaccine;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vaccine::create(['name' => 'Nobivac DHPPi', 'manufacturer' => 'Zoetis', 'sicknesses_treated' => 'Moquillo, Hepatitis, Parvovirosis, Parainfluenza']);
        Vaccine::create(['name' => 'Nobivac L4', 'manufacturer' => 'Zoetis', 'sicknesses_treated' => 'Leptospirosis']);
        Vaccine::create(['name' => 'Nobivac Rabia', 'manufacturer' => 'Zoetis', 'sicknesses_treated' => 'Rabia']);

        Vaccine::create(['name' => 'Eurican DAPPi-LR', 'manufacturer' => 'Boehringer Ingelheim', 'sicknesses_treated' => 'Moquillo, Hepatitis, Parvovirosis, Parainfluenza']);
        Vaccine::create(['name' => 'Eurican L', 'manufacturer' => 'Boehringer Ingelheim', 'sicknesses_treated' => 'Leptospirosis']);
        Vaccine::create(['name' => 'EURICAN R', 'manufacturer' => 'Boehringer Ingelheim', 'sicknesses_treated' => 'Rabia']);

        Vaccine::create(['name' => 'Canigen DHPPi', 'manufacturer' => 'Virbac', 'sicknesses_treated' => 'Moquillo, Hepatitis, Parvovirosis, Parainfluenza, Leptospirosis']);
        Vaccine::create(['name' => 'Canigen L', 'manufacturer' => 'Virbac', 'sicknesses_treated' => 'Leptospirosis']);
        Vaccine::create(['name' => 'Rabigen L', 'manufacturer' => 'Virbac', 'sicknesses_treated' => 'Rabia, Cepa VP12']);

        Vaccine::create(['name' => 'ETADEX', 'manufacturer' => 'CZ Vaccines S.A.U', 'sicknesses_treated' => 'VIRUS RABIA, INACTIVADO, CEPA FLURY LEP']);
    }
}
