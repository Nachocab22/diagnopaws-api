<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Gender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    private function generateDni()
    {
        $number = $this->faker->numberBetween(10000000, 99999999);
        $letters = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letter = $letters[$number % 23];

        return $number . $letter;
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'surname' => $this->faker->lastName(),
            'birth_date' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'dni' => $this->generateDni(),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
            'gender_id' => $this->faker->numberBetween(1, 4),
            'address_id' => Address::factory()->create()->id,
        ];
    }
}
