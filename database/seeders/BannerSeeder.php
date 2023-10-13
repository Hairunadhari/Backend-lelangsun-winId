<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BannerLelang::create([
            'judul' => 'Ikuti Lelang Dimana Saja',
            'deskripsi' => 'Dengan Fitur Live Auction Dan Time Auction',
            'status' => 'active',
        ]);
    }
}
