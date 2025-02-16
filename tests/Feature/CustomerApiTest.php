<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_customer_via_api()
    {
        $response = $this->postJson('/api/customers', [
            'name' => 'Hiệp đen',
            'email' => 'hiep@example.com',
            'phone' => '0123456789',
            'address' => '123 Street',
            'is_active' => true,
            'image' => null
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name', 'email', 'phone']);
    }

    /** @test */
    public function it_fetches_all_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }
}
