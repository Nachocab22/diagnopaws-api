<?php

namespace Tests\Unit;
use App\Http\Resources\PetResource;
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
    }

    #[Test] public function show_all_pets()
    {
        $pets = Pet::factory()->count(5)->create();

        $response = $this->getJson('api/pets');

        $response->assertOk()->assertJsonStructure([
            'data' => [
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
            fn(AssertableJson $json) => $json->has('data')->first(
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
        $pet = Pet::factory()->make();

        $response = $this->postJson('api/pets', $pet->toArray());

        $response->assertCreated()->assertJson(
            fn(AssertableJson $json) => $json->has('data')->first(
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
    }

    #[Test] public function it_fails_to_create_a_pet_with_invalid_data()
    {
        $response = $this->postJson('api/pets', []);

        $response->assertUnprocessable();
    }


    #[Test] public function updates_a_pet()
    {
        $pet = Pet::factory()->create(['name' => 'Old Name']);

        $this->putJson("api/pets/{$pet->id}", ['name' => 'New Name'])
            ->assertOk();

        $this->assertDatabaseHas('pets', ['id' => $pet->id, 'name' => 'New Name']);
    }


    #[Test] public function delete_a_pet()
    {
        $pet = Pet::factory()->create();

        $this->delete("api/pets/{$pet->id}")
            ->assertOk();

        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

}
