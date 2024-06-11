<?php

namespace Tests\Unit;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Pet;
use App\Models\User;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;

class PetTest extends TestCase
{
    use DatabaseTransactions;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(GenderSeeder::class);
        $this->seed(BreedSeeder::class);
        $this->seed(SpeciesSeeder::class);

        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test] public function show_all_pets()
    {
        $pets = Pet::factory()->count(5)->create();

        $response = $this->getJson('api/pets');

        $response->assertOk()->assertJsonStructure([
            'pets' => [
                '*' => [
                    'id', 'name', 'birth_date', 'color', 'sex', 'chip', 'breed', 'species'
                ]
            ]
        ]);
    }

    #[Test] public function show_one_pet()
    {
        $pet = Pet::factory()->create();

        $response = $this->getJson("api/pets/{$pet->id}");

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('pet')->first(
                fn(AssertableJson $json) => $json
                    ->where('id', $pet->id)
                    ->where('name', $pet->name)
                    ->where('birth_date', $pet->birth_date)
                    ->where('color', $pet->color)
                    ->where('sex', $pet->sex)
                    ->where('chip.number', $pet->chip_number)
                    ->where('chip.marking_date', $pet->chip_marking_date)
                    ->where('chip.position', $pet->chip_position)
                    ->has('breed')
                    ->has('species')
                    ->has('owner')
                    ->etc()
            )

        );
    }

    #[Test] public function creates_new_pet()
    {
        $this->user->assignRole('owner');
        $pet = Pet::factory()->make();

        $response = $this->postJson('api/pets', $pet->toArray());

        $response->assertCreated()->assertJson(
            fn(AssertableJson $json) => $json->has('pet')->first(
                fn(AssertableJson $json) => $json
                    ->where('name', $pet->name)
                    ->where('birth_date', $pet->birth_date)
                    ->where('color', $pet->color)
                    ->where('sex', $pet->sex)
                    ->where('chip.number', $pet->chip_number)
                    ->where('chip.marking_date', $pet->chip_marking_date)
                    ->where('chip.position', $pet->chip_position)
                    ->has('breed')
                    ->has('species')
                    ->has('owner')
                    ->etc()
            )
        );

        $this->assertDatabaseHas('pets', ['name' => $pet->name]);
        $this->user->removeRole('owner');
    }

    #[Test] public function it_fails_to_create_a_pet_with_invalid_data()
    {
        $this->user->assignRole('owner');
        $response = $this->postJson('api/pets', []);

        $response->assertUnprocessable();
        $this->user->removeRole('owner');
    }

    #[Test] public function updates_a_pet()
    {
        $this->user->assignRole('owner');

        $pet = Pet::factory()->create([
            'name' => 'Old Name',
            'birth_date' => '2020-01-01',
            'color' => 'Brown',
            'sex' => 'Male',
            'chip_number' => '1234567890',
            'chip_marking_date' => '2020-02-01',
            'chip_position' => 'Neck',
            'user_id' => $this->user->id,
            'breed_id' => 1,
        ]);

        $updateData = [
            'name' => 'New Name',
            'birth_date' => '2020-01-01',
            'color' => 'Brown',
            'sex' => 'Male',
            'chip_number' => '1234567890',
            'chip_marking_date' => '2020-02-01',
            'chip_position' => 'Neck',
            'user_id' => $this->user->id,
            'breed_id' => 1,
            'image' => null,
        ];

        $this->putJson("api/pets/{$pet->id}", $updateData)
            ->assertOk();

        $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'New Name']);

        $this->user->removeRole('owner');
    }


    #[Test] public function delete_a_pet()
    {
        $this->user->assignRole('owner');
        $pet = Pet::factory()->create();

        $this->delete("api/pets/{$pet->id}")
            ->assertOk();

        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
        $this->user->removeRole('owner');
    }

    #[Test] public function show_pets_of_authenticated_user()
    {
        $this->user->assignRole('owner');
        $pets = Pet::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('api/user/pets');

        $response->assertOk()->assertJsonStructure([
            'pets' => [
                '*' => [
                    'id', 'name', 'birth_date', 'color', 'sex', 'chip', 'breed', 'species'
                ]
            ]
        ]);
        $this->user->removeRole('owner');
    }

    #[Test] public function search_pets()
    {
        $this->user->assignRole('owner');
        $pet = Pet::factory()->create(['name' => 'Firulais']);

        $response = $this->getJson('api/pets/search/Firulais');

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('pets.0',
                fn(AssertableJson $json) => $json
                    ->where('id', $pet->id)
                    ->where('name', $pet->name)
                    ->where('birth_date', $pet->birth_date)
                    ->where('color', $pet->color)
                    ->where('sex', $pet->sex)
                    ->where('chip.number', $pet->chip_number)
                    ->where('chip.marking_date', $pet->chip_marking_date)
                    ->where('chip.position', $pet->chip_position)
                    ->has('breed')
                    ->has('species')
                    ->has('owner')
                    ->has('vaccinations')
                    ->etc()
            )
        );
        $this->user->removeRole('owner');
    }

}
