<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $destinations = Destination::all();

        Package::factory(3)
            ->hasAttached($destinations)
            ->create();
    }
}
