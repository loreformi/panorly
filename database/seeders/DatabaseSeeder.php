<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Themes ship as presets in resources/css/themes, no seeding required.
        // First registered user automatically becomes admin (see RegisteredUserController).
    }
}
