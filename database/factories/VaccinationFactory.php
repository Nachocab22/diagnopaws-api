<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Vaccination;
use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vaccination>
 */
class VaccinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vaccination_date' => $this->faker->dateTimeThisYear(),
            'next_vaccination_date' => $this->faker->dateTimeThisYear(),
            'lot_number' => $this->faker->numberBetween(1000, 9999),
            'pet_id' => Pet::get('id')->random(),
            'vaccine_id' => Vaccine::get('id')->random(),

        ];
    }
}
