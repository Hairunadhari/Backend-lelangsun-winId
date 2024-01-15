<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KurirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Kurir::create([
            'kurir' => 'pos',
        ]);

        \App\Models\Kurir::create([
            'kurir' => 'tiki',
        ]);
        \App\Models\Kurir::create([
            'kurir' => 'jne',
        ]);
    }
}
