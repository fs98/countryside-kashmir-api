<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AuthorSeeder::class,
            SlideSeeder::class,
            MessageSeeder::class,
            CategorySeeder::class,
            BlogSeeder::class,
            DestinationSeeder::class,
            DestinationImageSeeder::class,
            PackageSeeder::class,
            PackageImageSeeder::class,
            ActivitySeeder::class,
            ActivityImageSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
