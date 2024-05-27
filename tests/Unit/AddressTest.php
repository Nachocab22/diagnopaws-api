<?php

namespace Tests\Unit;
use App\Models\Address;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Database\Seeders\BreedSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SpeciesSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;

class AddressTest extends TestCase
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

    #[Test] public function show_address()
    {
        $address = Address::factory()->create();

        $response = $this->getJson("api/addresses/{$address->id}");
        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('data')->first(
                fn(AssertableJson $json) => $json
                    ->where('id', $address->id)
                    ->where('street', $address->street)
                    ->where('number', $address->number)
                    ->where('flat', $address->flat)
                    ->has('town')
                    ->etc()
            )
        );
    }

    #[Test] public function create_new_address() {

        $address = Address::factory()->make();
        var_dump($address->toArray());

        $response = $this->postJson("api/addresses", $address->toArray());

        $response->assertCreated()->assertJson(
            fn(AssertableJson $json) => $json->has('address')->first(
                fn(AssertableJson $json) => $json
                    ->where('street', $address->street)
                    ->where('number', $address->number)
                    ->where('flat', $address->flat)
                    ->has('town')
                    ->etc()
            )
        );

        $this->assertDatabaseHas('addresses', $address->toArray());
    }

    #[Test] public function cannot_create_address_with_invalid_data() {
        $address = Address::factory()->make(['street' => '']);

        $response = $this->postJson("api/addresses", $address->toArray());

        $response->assertUnprocessable();
    }

    #[Test] public function return_address_with_same_data()
    {
        $address = Address::factory()->create(['street' => 'Test Street', 'number' => 1, 'flat' => 1, 'town_id' => 1]);

        $newAddress = Address::factory()->make(['street' => 'Test Street', 'number' => 1, 'flat' => 1, 'town_id' => 1]);

        $response = $this->postJson("api/addresses", $newAddress->toArray());

        $response->assertOk()->assertJson(
            fn(AssertableJson $json) => $json->has('address')->first(
                fn(AssertableJson $json) => $json
                    ->where('id', $address->id)
                    ->where('street', $newAddress->street)
                    ->where('number', $newAddress->number)
                    ->where('flat', $newAddress->flat)
                    ->where('town.id', $newAddress->town_id)
                    ->etc()
            )
        );
    }
}
