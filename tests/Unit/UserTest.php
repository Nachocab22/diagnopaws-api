<?php

namespace Tests\Unit;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(GenderSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(SpeciesSeeder::class);
    }

    #[Test] public function show_all_users()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->getJson('api/users');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'surname', 'birth_date', 'dni', 'phone', 'email', 'gender', 'address'
                ]
            ]
        ]);

        $users->each->delete();
    }

    #[Test] public function show_one_user()
    {
        $user = User::factory()->create();
        $user->birth_date->setTimezone('UTC');

        $response = $this->getJson("api/users/{$user->id}");

        $response->assertStatus(200);

        $expectedData = [
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'birth_date' => $user->birth_date->format('Y-m-d'),
                'dni' => $user->dni,
                'phone' => $user->phone,
                'email' => $user->email,
                'gender' => [
                    'id' => $user->gender_id,
                    'name' => $user->gender->name,
                ],
                'address' => [
                    'id' => $user->address_id,
                    'street' => $user->address->street,
                    'number' => $user->address->number,
                    'flat' => $user->address->flat,
                    'town' => [
                        'id' => $user->address->town_id,
                        'name' => $user->address->town->name,
                    ],
                ],
            ]
        ];

        $response->assertJson($expectedData);

        $user->delete();
    }

    #[Test] public function create_new_user()
    {
        $address = Address::factory()->create();
        $user = [
            'name' => 'Pedro',
            'surname' => 'Pérez',
            'birth_date' => '1990-01-01',
            'dni' => '32090108W',
            'phone' => '643726437',
            'email' => 'pedro@correo.es',
            'password' => 'password',
            'gender_id' => 1,
            'address_id' => $address->id,
        ];

        $response = $this->postJson('api/users', $user);

        $response->assertCreated()
                ->assertJson(
                    fn(AssertableJson $json) => $json->has('data')->first(
                        fn(AssertableJson $json) => $json
                        ->where('name', 'Pedro')
                        ->where('surname', 'Pérez')
                        ->where('birth_date', '1990-01-01')
                        ->where('dni', '32090108W')
                        ->where('phone', '643726437')
                        ->where('email', 'pedro@correo.es')
                        ->missing('password')
                        ->where('gender.id', 1)
                        ->where('gender.name', 'Hombre')
                        ->where('address.id', $address->id)
                        ->etc()
                    )
        );
        $this->assertDatabaseHas('users', [
            'email' => 'pedro@correo.es'
        ]);
    }

    // Pruebas para el Registro de Usuarios

    #[Test] public function user_registration_fails_due_to_validation_errors()
    {
        $userData = [
            'name' => '', // Campo requerido vacío
            'surname' => 'Doe',
            'birth_date' => 'not a date', // Fecha inválida
            'dni' => '123456789W',
            'phone' => '123456789',
            'email' => 'john@example', // Email inválido
            'password' => 'pass', // Contraseña demasiado corta
            'gender_id' => 'not a number', // ID inválido
            'address_id' => 1
        ];

        $nUsers = DB::table('users')->count();

        $response = $this->postJson('api/users', $userData);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', $nUsers);
    }

    #[Test] public function user_registration_fails_due_to_duplicate_email()
    {
        $existingUser = User::factory()->create(['email' => 'john@example.com']);

        $userData = [
            'name' => 'John Doe',
            'surname' => 'Doe',
            'birth_date' => '1990-01-01',
            'dni' => '123456789',
            'phone' => '1234567890',
            'email' => 'john@example.com', // Email duplicado
            'password' => 'password',
            'gender_id' => 1,
            'address_id' => 1
        ];

        $nUsers = DB::table('users')->count();

        $response = $this->postJson('api/users', $userData);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', $nUsers);
    }

    // Pruebas para el Login de Usuarios
    #[Test] public function can_login_with_valid_credentials()
    {
        $user = User::factory()->create(['password' => bcrypt('valid_password')]);

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'valid_password'
        ]);

        $response->assertOk()->assertJson(
          ['message' => 'Logged in']
        );
    }

    #[Test] public function cannot_login_with_invalid_mail()
    {
        $user = User::factory()->create(['email' => 'invalid', 'password' => bcrypt('valid_password')]);

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'valid_password'
        ]);

        $response->assertStatus(422);
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('message')
                ->where('message', 'The email field must be a valid email address.')
                ->etc()
        );

        $user->delete();
    }

    #[Test] public function cannot_login_with_invalid_password()
    {
        $user = User::factory()->create(['password' => bcrypt('invalid')]);

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'other_password'
        ]);

        $response->assertUnauthorized()->assertJson(
            fn(AssertableJson $json) => $json->has('message')
                ->where('message', 'Invalid credentials')
                ->etc()
        );
    }
}
