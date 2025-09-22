<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Bug;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'admin'
        ]);
    }

    public function test_user_has_many_bugs()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['reporter_id' => $user->id]);

        $this->assertTrue($user->bugs->contains($bug));
        $this->assertEquals(1, $user->bugs->count());
    }

    public function test_user_has_many_assigned_bugs()
    {
        $user = User::factory()->create();
        $bug = Bug::factory()->create(['assigned_to' => $user->id]);

        $this->assertTrue($user->assignedBugs->contains($bug));
        $this->assertEquals(1, $user->assignedBugs->count());
    }

    public function test_user_has_many_projects()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['created_by' => $user->id]);

        $this->assertTrue($user->projects->contains($project));
        $this->assertEquals(1, $user->projects->count());
    }

    public function test_user_has_many_notifications()
    {
        $user = User::factory()->create();
        $notification = $user->notifications()->create([
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'type' => 'info'
        ]);

        $this->assertTrue($user->notifications->contains($notification));
        $this->assertEquals(1, $user->notifications->count());
    }

    public function test_user_can_check_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->can('view-users'));
        $this->assertTrue($admin->can('create-users'));
        $this->assertTrue($admin->can('edit-users'));
        $this->assertTrue($admin->can('delete-users'));

        $this->assertFalse($user->can('view-users'));
        $this->assertFalse($user->can('create-users'));
        $this->assertFalse($user->can('edit-users'));
        $this->assertFalse($user->can('delete-users'));
    }

    public function test_user_can_check_bug_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->can('view-bugs'));
        $this->assertTrue($admin->can('create-bugs'));
        $this->assertTrue($admin->can('edit-bugs'));
        $this->assertTrue($admin->can('delete-bugs'));

        $this->assertTrue($user->can('view-bugs'));
        $this->assertTrue($user->can('create-bugs'));
        $this->assertFalse($user->can('edit-bugs'));
        $this->assertFalse($user->can('delete-bugs'));
    }

    public function test_user_can_check_project_permissions()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->can('view-projects'));
        $this->assertTrue($admin->can('create-projects'));
        $this->assertTrue($admin->can('edit-projects'));
        $this->assertTrue($admin->can('delete-projects'));

        $this->assertTrue($user->can('view-projects'));
        $this->assertTrue($user->can('create-projects'));
        $this->assertFalse($user->can('edit-projects'));
        $this->assertFalse($user->can('delete-projects'));
    }

    public function test_user_has_avatar_attribute()
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        $this->assertEquals('JD', $user->avatar);
    }

    public function test_user_has_full_name_attribute()
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        $this->assertEquals('John Doe', $user->full_name);
    }

    public function test_user_is_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_user_is_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertFalse($admin->isUser());
        $this->assertTrue($user->isUser());
    }
}
