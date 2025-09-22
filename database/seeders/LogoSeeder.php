<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize logo setting as null (no logo uploaded initially)
        SystemSetting::set('logo_path', null);

        $this->command->info('Logo settings initialized successfully!');
    }
}
