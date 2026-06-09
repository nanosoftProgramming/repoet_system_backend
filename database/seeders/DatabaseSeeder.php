<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;
use Modules\Survey\Database\Seeders\SurveyDatabaseSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserDatabaseSeeder::class,
            SurveyDatabaseSeeder::class,
        ]);
    }
}
