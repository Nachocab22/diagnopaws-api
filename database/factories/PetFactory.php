<?php

namespace Database\Factories;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'color' => $this->faker->colorName,
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'chip_number' => (string) $this->faker->numberBetween(100000000000000, 999999999999999),
            'chip_marking_date' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'chip_position' => $this->faker->randomElement(['Left side of the neck', 'Right side of the neck', 'Left side of the chest', 'Right side of the chest', 'Back']),
            'breed_id' => $this->faker->numberBetween(1, 188),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
