<?php

namespace Database\Factories;

use App\Models\Address;
use Flogti\SpanishCities\Models\Town;
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
        $townId = Town::query()->inRandomOrder()->first()->id ?? 1;
        return [
            'street' => $this->faker->streetName,
            'number' => (integer) $this->faker->numberBetween(1, 100),
            'flat' => $this->faker->numberBetween(1, 20) . strtoupper($this->faker->randomLetter),
            'town_id' => $townId,
        ];
    }
}
