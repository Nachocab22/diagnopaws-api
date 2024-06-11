<?php

namespace Tests\Unit;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Tests\TestCase;
use App\Models\Address;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    public function authenticateUser(): void
    {
        Sanctum::actingAs($this->user, ['*']);
    }
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(GenderSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(SpeciesSeeder::class);

        $this->user = User::factory()->create();
    }

    #[Test] public function show_all_users()
    {
        $this->authenticateUser();
        $users = User::factory()->count(5)->create();

        $response = $this->getJson('api/users');

        $response->assertOk()->assertJsonStructure([
            'users' => [
                '*' => [
                    'id', 'name', 'surname', 'birth_date', 'dni', 'phone', 'email', 'gender', 'address'
                ]
            ]
        ]);
    }
    #[Test] public function show_one_user()
    {
        $this->authenticateUser();
        $user = User::factory()->create();

        $response = $this->getJson("api/users/{$user->id}");

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('user')->first(
                fn(AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('surname', $user->surname)
                    ->where('birth_date', $user->birth_date->format('Y-m-d'))
                    ->where('dni', $user->dni)
                    ->where('phone', $user->phone)
                    ->where('email', $user->email)
                    ->where('gender.name', $user->gender->name)
                    ->where('address.id', $user->address->id)
                    ->etc()
            )
        );
    }
    #[Test] public function register_new_user()
    {
        $address = Address::factory()->create();
        $user = [
            'name' => 'Pedro',
            'surname' => 'Pérez',
            'birth_date' => '1990-01-01',
            'dni' => '77783282L',
            'phone' => '643726437',
            'email' => 'pedro@correo.es',
            'password' => 'password',
            'gender_id' => 1,
            'address_id' => $address->id,
        ];

        $response = $this->postJson('api/register', $user);

        $response->assertCreated()
                ->assertJson(
                    fn(AssertableJson $json) => $json->has('user')->first(
                        fn(AssertableJson $json) => $json
                        ->where('name', 'Pedro')
                        ->where('surname', 'Pérez')
                        ->where('birth_date', '1990-01-01')
                        ->where('dni', '77783282L')
                        ->where('phone', '643726437')
                        ->where('email', 'pedro@correo.es')
                        ->missing('password')
                        ->where('gender.id', 1)
                        ->where('gender.name', 'Hombre')
                        ->where('address.id', $address->id)
                        ->where('role', ['owner'])
                        ->etc()
                    )
        );
        $this->assertDatabaseHas('users', [
            'email' => 'pedro@correo.es'
        ]);
        $this->user->removeRole('admin');
    }
    #[Test] public function owner_cannot_create_new_user()
    {
        $this->authenticateUser();
        $this->user->assignRole('owner');
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

        $response->assertForbidden();
        $this->user->removeRole('owner');
    }
    // Pruebas para el Registro de Usuarios
    #[Test] public function user_registration_fails_due_to_validation_errors()
    {
        $this->authenticateUser();
        $this->user->assignRole('admin');
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

        $response = $this->postJson('api/register', $userData);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', $nUsers);
        $this->user->removeRole('admin');
    }
    #[Test] public function user_registration_fails_due_to_duplicate_email()
    {
        $this->authenticateUser();
        $this->user->assignRole('admin');
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

        $response = $this->postJson('api/register', $userData);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', $nUsers);
        $this->user->removeRole('admin');
    }
    // Método de prueba para verificar la sesión de Sanctum
    #[Test] public function can_access_authenticated_route()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('api/user');

        $response->assertOk()->assertJsonStructure([
            'name',
            'surname',
            'birth_date',
            'dni',
            'phone',
            'email',
        ]);
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
    #[Test] public function search_user ()
    {
        $this->authenticateUser();
        $user = User::factory()->create(['name' => 'PruebaTest']);
        $response = $this->getJson("api/users/search/PruebaTest");
        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('users.0',
                fn(AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('surname', $user->surname)
                    ->where('birth_date', $user->birth_date->format('Y-m-d'))
                    ->where('dni', $user->dni)
                    ->where('phone', $user->phone)
                    ->where('email', $user->email)
                    ->etc()
            ));
    }
    #[Test] public function modify_role()
    {
        $this->authenticateUser();
        $this->user->assignRole('admin');

        $user = User::factory()->create();
        $user->assignRole('owner');

        $response = $this->putJson("api/user/role/{$user->id}", ['role' => ['vet']])
            ->assertOk();

        $this->assertDatabaseHas('model_has_roles', ['model_id' => $user->id, 'role_id' => 2]);

        $this->user->removeRole('admin');
    }
}

