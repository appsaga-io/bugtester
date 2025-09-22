<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    public function test_admin_can_view_users_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/users');

        $response->assertStatus(200);
        $response->assertSee('User Management');
    }

    public function test_admin_can_create_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Users\Index();
        $component->name = 'Test User';
        $component->email = 'test@example.com';
        $component->password = 'password123';
        $component->password_confirmation = 'password123';
        $component->role = 'tester';

        $component->createUser();

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'tester'
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('tester'));
    }

    public function test_admin_can_edit_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $user = User::factory()->create(['role' => 'tester']);
        $user->assignRole('tester');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Users\Index();
        $component->editingUser = $user;
        $component->name = 'Updated Name';
        $component->email = 'updated@example.com';
        $component->role = 'developer';
        $component->password = '';
        $component->password_confirmation = '';

        $component->updateUser();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'developer'
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $user = User::factory()->create(['role' => 'tester']);
        $user->assignRole('tester');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Users\Index();
        $component->deleteUser($user->id);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function test_admin_cannot_delete_themselves()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Users\Index();
        $component->deleteUser($admin->id);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id
        ]);
    }

    public function test_non_admin_cannot_access_users_page()
    {
        $tester = User::factory()->create(['role' => 'tester']);
        $tester->assignRole('tester');

        $response = $this->actingAs($tester)->get('/users');

        $response->assertStatus(403);
    }

    public function test_developer_cannot_access_users_page()
    {
        $developer = User::factory()->create(['role' => 'developer']);
        $developer->assignRole('developer');

        $response = $this->actingAs($developer)->get('/users');

        $response->assertStatus(403);
    }
}
