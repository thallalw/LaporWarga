<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            'Blok A', 'Blok B', 'Blok C', 
            'Balai Warga', 'Taman Bermain Anak Anak', 'Pos Satpam'
        ];

        foreach ($areas as $area) {
            Area::create(['name' => $area]);
        }
    }
}
