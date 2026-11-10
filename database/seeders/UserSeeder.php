<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Ketua RT
       User::create([
    'name' => 'Ketua RT',
    'username' => 'ketua_rt',
    'role' => 'rt',
    'area_id' => null,
    'password' => Hash::make('RukunTetangga2'),
]);

        // Akun Ketua RW
        User::create([
            'name' => 'Ketua RW',
            'username' => 'ketua_rw',
            'role' => 'rw',
            'area_id' => null,
            'password' => Hash::make('RukunWarga12'),
        ]);
    }
}