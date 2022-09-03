<?php

namespace Database\Seeders;

use App\Models\Image as DestinationImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DestinationImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DestinationImage::factory(10)->create();
    }
}
