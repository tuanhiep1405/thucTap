<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_welcome_email_when_user_registers()
    {
        Queue::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201);

        Queue::assertPushed(SendWelcomeEmail::class);
    }
}

