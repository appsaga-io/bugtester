<?php

namespace Tests\Unit\Models;

use App\Models\SystemSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_setting_can_be_created()
    {
        $setting = SystemSetting::create([
            'key' => 'app_name',
            'value' => 'BugTester'
        ]);

        $this->assertDatabaseHas('system_settings', [
            'key' => 'app_name',
            'value' => 'BugTester'
        ]);
    }

    public function test_get_setting_returns_value()
    {
        SystemSetting::create([
            'key' => 'app_name',
            'value' => 'BugTester'
        ]);

        $value = SystemSetting::get('app_name');
        $this->assertEquals('BugTester', $value);
    }

    public function test_get_setting_returns_default()
    {
        $value = SystemSetting::get('non_existent_key', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    public function test_set_setting_creates_new()
    {
        SystemSetting::set('app_name', 'BugTester');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'app_name',
            'value' => 'BugTester'
        ]);
    }

    public function test_set_setting_updates_existing()
    {
        SystemSetting::create([
            'key' => 'app_name',
            'value' => 'Old Name'
        ]);

        SystemSetting::set('app_name', 'New Name');

        $this->assertDatabaseHas('system_settings', [
            'key' => 'app_name',
            'value' => 'New Name'
        ]);

        $this->assertEquals(1, SystemSetting::where('key', 'app_name')->count());
    }

    public function test_has_logo_returns_true_when_logo_exists()
    {
        SystemSetting::set('logo_path', 'logos/logo.jpg');

        $this->assertTrue(SystemSetting::hasLogo());
    }

    public function test_has_logo_returns_false_when_no_logo()
    {
        $this->assertFalse(SystemSetting::hasLogo());
    }

    public function test_get_logo_url_returns_url()
    {
        SystemSetting::set('logo_path', 'logos/logo.jpg');

        $url = SystemSetting::getLogoUrl();
        $this->assertStringContains('logos/logo.jpg', $url);
    }

    public function test_get_logo_url_returns_null_when_no_logo()
    {
        $url = SystemSetting::getLogoUrl();
        $this->assertNull($url);
    }
}
