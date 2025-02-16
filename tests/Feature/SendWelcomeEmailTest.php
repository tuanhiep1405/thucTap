<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_send_welcome_email_job()
    {
        // Giả lập hàng đợi
        Queue::fake();

        // Tạo một user giả lập
        $user = User::factory()->create();

        // Dispatch job
        SendWelcomeEmail::dispatch($user);

        // Kiểm tra job có thực sự được đẩy vào queue hay không
        Queue::assertPushed(SendWelcomeEmail::class, function ($job) use ($user) {
            return $job->getUser()->id === $user->id;
        });
    }
}

