<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'working_hours'],
            ['value' => json_encode(['start' => '09:00', 'end' => '18:00'])]
        );
        
        // Add more settings here as needed
    }
}
