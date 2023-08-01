<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'title' => 'Balai Lelang SUN',
            'deskripsi' => 'Balai Lelang SUN',
            'no_telp' => '02122271959',
            'wa' => '081296367771',
            'email' => 'lelangsun@gmail.com',
            'alamat' => 'Ciledug Mas, Ruko, Jl. HOS Cokroaminoto No.D3, RT.003/RW.004, Karang Tim., Kec. Ciledug, Kota Tangerang, Banten 15157',
            'ig' => 'https://www.instagram.com/balailelangsun.id/?hl=en',
            'fb' => '#!',
            'twitter' => '#!',
            'yt' => 'https://www.youtube.com/channel/UCD9ve-bPrPuIorZgF8kubVA',
        ]);
    }
}
