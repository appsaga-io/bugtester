<?php

namespace Tests\Unit\Services;

use App\Services\NotificationService;
use App\Models\User;
use App\Models\Bug;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_send_notification()
    {
        $user = User::factory()->create();
        $service = new NotificationService();

        $service->send($user, 'Test Title', 'Test Message', 'info');

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Test Title',
            'message' => 'Test Message',
            'type' => 'info'
        ]);
    }

    public function test_can_send_bug_assigned_notification()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();
        $service = new NotificationService();

        $service->bugAssigned($user, $bug);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Bug Assigned',
            'message' => "You have been assigned to bug: {$bug->title}",
            'type' => 'info'
        ]);
    }

    public function test_can_send_bug_updated_notification()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();
        $service = new NotificationService();

        $service->bugUpdated($user, $bug);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Bug Updated',
            'message' => "Bug '{$bug->title}' has been updated",
            'type' => 'info'
        ]);
    }

    public function test_can_send_bug_resolved_notification()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create();
        $service = new NotificationService();

        $service->bugResolved($user, $bug);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Bug Resolved',
            'message' => "Bug '{$bug->title}' has been resolved",
            'type' => 'success'
        ]);
    }

    public function test_can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $notification = Notification::factory()->create([
            'user_id' => $user->id,
            'read_at' => null
        ]);

        $service = new NotificationService();
        $service->markAsRead($notification);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        Notification::factory()->count(3)->create([
            'user_id' => $user->id,
            'read_at' => null
        ]);

        $service = new NotificationService();
        $service->markAllAsRead($user);

        $unreadCount = $user->notifications()->whereNull('read_at')->count();
        $this->assertEquals(0, $unreadCount);
    }

    public function test_can_get_unread_count()
    {
        $user = User::factory()->create();
        Notification::factory()->count(2)->create([
            'user_id' => $user->id,
            'read_at' => null
        ]);
        Notification::factory()->create([
            'user_id' => $user->id,
            'read_at' => now()
        ]);

        $service = new NotificationService();
        $count = $service->getUnreadCount($user);

        $this->assertEquals(2, $count);
    }
}
