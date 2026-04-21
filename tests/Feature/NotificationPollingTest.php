<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

class NotificationPollingTest extends TestCase
{
    use RefreshDatabase;

    public function test_notifications_endpoint_returns_unread_notifications_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $user->notify(new class extends Notification
        {
            public function via(object $notifiable): array
            {
                return ['database'];
            }

            public function toDatabase(object $notifiable): array
            {
                return [
                    'message' => 'Polling notification test message',
                ];
            }
        });

        $response = $this->actingAs($user)->getJson('/notifications');

        $response
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('notifications.0.message', 'Polling notification test message');
    }
}

