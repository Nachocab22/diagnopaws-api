<?php

namespace Tests\Unit;
use App\Models\Vaccination;
use App\Models\Vaccine;
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

class VaccinationTest extends TestCase
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

    #[Test] public function show_all_vaccinations()
    {
        $vaccinations = Vaccination::factory()->count(5)->create();

        $response = $this->getJson('api/vaccinations');

        $response->assertOk()->assertJsonStructure([
            'vaccinations' => [
                '*' => [
                    'vaccination_date','vaccine', 'next_vaccination_date', 'sicknesses_treated'
                ]
            ]
        ]);
    }

    #[Test] public function show_one_vaccination()
    {
        $vaccination = Vaccination::factory()->create();

        $response = $this->getJson("api/vaccinations/{$vaccination->id}");

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('vaccination')->first(
                fn(AssertableJson $json) => $json
                    ->where('vaccine.name', $vaccination->vaccine->name)
                    ->where('vaccination_date', $vaccination->vaccination_date)
                    ->where('next_vaccination_date', $vaccination->next_vaccination_date)
                    ->where('sicknesses_treated', $vaccination->vaccine->sicknesses_treated)
                    ->etc()
            )
        );
    }

    #[Test] public function create_new_vaccination()
    {
        $this->user->assignRole('vet');
        $vaccination = Vaccination::factory()->make();

        $response = $this->postJson('api/vaccinations', $vaccination->toArray());

        $response->assertCreated()->assertJson(
            fn(AssertableJson $json) => $json->has('vaccination')->first(
                fn(AssertableJson $json) => $json
                    ->where('vaccine.name', $vaccination->vaccine->name)
                    ->where('vaccination_date', $vaccination->vaccination_date)
                    ->where('next_vaccination_date', $vaccination->next_vaccination_date)
                    ->where('sicknesses_treated', $vaccination->vaccine->sicknesses_treated)
                    ->etc()
            )
        );

        $this->assertDatabaseHas('vaccinations', [
            'vaccine_id' => $vaccination->vaccine_id,
            'vaccination_date' => $vaccination->vaccination_date,
            'next_vaccination_date' => $vaccination->next_vaccination_date,
        ]);
        $this->user->removeRole('vet');
    }

    #[Test] public function owner_cannot_create_new_vaccination()
    {
        $this->user->assignRole('owner');
        $vaccination = Vaccination::factory()->make();

        $response = $this->postJson('api/vaccinations', $vaccination->toArray());

        $response->assertForbidden();
        $this->user->removeRole('owner');
    }

    #[Test] public function updates_a_vaccination()
    {
        $this->user->assignRole('vet');
        $vaccination = Vaccination::factory()->create();
        $newVaccination = Vaccination::factory()->make();

        $response = $this->putJson("api/vaccinations/{$vaccination->id}", $newVaccination->toArray());

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('vaccination')->first(
                fn(AssertableJson $json) => $json
                    ->where('id', $vaccination->id)
                    ->where('vaccine.id', $newVaccination->vaccine_id)
                    ->where('vaccination_date', $newVaccination->vaccination_date)
                    ->where('next_vaccination_date', $newVaccination->next_vaccination_date)
                    ->where('sicknesses_treated', $newVaccination->vaccine->sicknesses_treated)
                    ->etc()
            )
        );

        $this->assertDatabaseHas('vaccinations', [
            'vaccine_id' => $newVaccination->vaccine_id,
            'vaccination_date' => $newVaccination->vaccination_date,
            'next_vaccination_date' => $newVaccination->next_vaccination_date,
        ]);
        $this->user->removeRole('vet');
    }

    #[Test] public function owner_cannot_update_a_vaccination()
    {
        $this->user->assignRole('owner');
        $vaccination = Vaccination::factory()->create();
        $newVaccination = Vaccination::factory()->make();

        $response = $this->putJson("api/vaccinations/{$vaccination->id}", $newVaccination->toArray());

        $response->assertForbidden();
        $this->user->removeRole('owner');
    }

    #[Test] public function delete_a_vaccination()
    {
        $this->user->assignRole('vet');
        $vaccination = Vaccination::factory()->create();

        $this->deleteJson("api/vaccinations/{$vaccination->id}")
            ->assertOk();

        $this->assertDatabaseMissing('vaccinations', ['id' => $vaccination->id]);
        $this->user->removeRole('vet');
    }

}
