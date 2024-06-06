<?php

namespace Tests\Unit;

namespace Tests\Unit;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Vaccine;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;

class VaccineTest extends TestCase
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

    #[Test] public function show_all_vaccines()
    {
        $vaccines = Vaccine::factory()->count(5)->create();

        $response = $this->getJson('api/vaccines');

        $response->assertOk()->assertJsonStructure([
            'vaccines' => [
                '*' => [
                    'id', 'name', 'manufacturer'
                ]
            ]
        ]);
    }

    #[Test] public function create_new_vaccine(){
        $this->user->assignRole('admin');
        $vaccine = Vaccine::factory()->make();

        $response = $this->postJson('api/vaccines', $vaccine->toArray());
        $response->assertCreated()->assertJson(
            fn(AssertableJson $json) => $json->has('vaccine')->first(
                fn(AssertableJson $json) => $json
                    ->where('name', $vaccine->name)
                    ->where('manufacturer', $vaccine->manufacturer)
                    ->etc()
            )
        );
        $this->assertDatabaseHas('vaccines', ['name' => $vaccine->name]);
        $this->user->removeRole('admin');
    }

    #[Test] public function non_admin_cannot_create_vaccine()
    {
        $this->user->assignRole('vet');
        $vaccine = Vaccine::factory()->create();

        $response = $this->postJson('api/vaccines', $vaccine->toArray());
        $response->assertForbidden();
        $this->user->removeRole('vet');
    }

    #[Test] public function delete_vaccine()
    {
        $this->user->assignRole('admin');
        $vaccine = Vaccine::factory()->create();

        $this->deleteJson("api/vaccines/{$vaccine->id}")
            ->assertOk();

        $this->assertDatabaseMissing('vaccines', ['id' => $vaccine->id]);
        $this->user->removeRole('admin');
    }

    #[Test] public function search_vaccine(){
        $vaccine = Vaccine::factory()->create(['name' => 'Vaccine 1']);
        $vaccine2 = Vaccine::factory()->create(['name' => 'Vaccine 2']);

        $response = $this->getJson('api/vaccines/search/Vaccine 1');
        $response->assertOk()->assertJsonCount(1, 'vaccines');
    }
}
