<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'flat' => $this->faker->numberBetween(1, 20),
            'town_id' => $this->faker->numberBetween(1, 7996),
        ];
    }
}
