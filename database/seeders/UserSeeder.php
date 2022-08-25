<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::factory()->create([
            'email' => 'superadmin@countrysidekashmir.com',
        ]);
        $superadmin->assignRole('Super Admin');

        $superadmin = User::factory()->create([
            'email' => 'admin@countrysidekashmir.com',
        ]);
        $superadmin->assignRole('Admin');

        $superadmin = User::factory()->create([
            'email' => 'client@countrysidekashmir.com',
        ]);
        $superadmin->assignRole('Client');
    }
}
