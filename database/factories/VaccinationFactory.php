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
            'vaccination_date' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'next_vaccination_date' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'lot_number' => $this->faker->numberBetween(1000, 9999),
            'pet_id' => Pet::factory()->create(),
            'vaccine_id' => $this->faker->numberBetween(1, 10),

        ];
    }
}
