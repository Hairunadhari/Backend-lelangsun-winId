<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        \App\Models\Role::create([
            'role' => 'Super Admin',
        ]);

        \App\Models\Role::create([
            'role' => 'User Admin',
        ]);
    }
}
