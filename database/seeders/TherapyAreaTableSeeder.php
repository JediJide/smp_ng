<?php

namespace Database\Seeders;

use App\Models\TherapyArea;
use Illuminate\Database\Seeder;

class TherapyAreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $therapy_areas = [
            [
                'id' => 1,
                'name' => 'EtranaDez',
            ],
        ];

        TherapyArea::insert($therapy_areas);
    }
}
