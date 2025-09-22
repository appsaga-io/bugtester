<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Initialize logo settings
        $this->artisan('db:seed', ['--class' => 'LogoSeeder']);

        Storage::fake('public');
    }

    public function test_admin_can_view_logo_management_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/logo');

        $response->assertStatus(200);
        $response->assertSee('Logo Management');
    }

    public function test_admin_can_upload_logo()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Admin\LogoManagement();
        $component->logo = UploadedFile::fake()->image('logo.png', 200, 60);

        $component->uploadLogo();

        $this->assertNotNull(SystemSetting::get('logo_path'));
        $this->assertTrue(Storage::disk('public')->exists(SystemSetting::get('logo_path')));
    }

    public function test_admin_can_remove_logo()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        // Set a logo first
        SystemSetting::set('logo_path', 'logos/test-logo.png');
        Storage::disk('public')->put('logos/test-logo.png', 'fake content');

        $this->actingAs($admin);

        // Test the Livewire component directly
        $component = new \App\Livewire\Admin\LogoManagement();
        $component->removeLogo();

        $this->assertNull(SystemSetting::get('logo_path'));
    }

    public function test_non_admin_cannot_access_logo_management()
    {
        $tester = User::factory()->create(['role' => 'tester']);
        $tester->assignRole('tester');

        $response = $this->actingAs($tester)->get('/admin/logo');

        $response->assertStatus(403);
    }

    public function test_navigation_displays_uploaded_logo()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        // Set a logo
        $logoPath = 'logos/test-logo.png';
        SystemSetting::set('logo_path', $logoPath);
        Storage::disk('public')->put($logoPath, 'fake content');

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(Storage::disk('public')->url($logoPath));
    }

    public function test_navigation_falls_back_to_svg_logo_when_no_uploaded_logo()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');

        // Ensure no logo is set
        SystemSetting::set('logo_path', null);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        // Should not see uploaded logo URL
        $response->assertDontSee('storage/logos/');
    }
}
