<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_customer_correctly()
    {
        $customer = Customer::create([
            'name' => 'Hiệp đen',
            'email' => 'hiep@example.com',
            'phone' => '0123456789',
            'address' => '123 Street',
            'is_active' => true,
            'image' => null
        ]);

        $this->assertDatabaseHas('customers', ['email' => 'hiep@example.com']);
    }
}
