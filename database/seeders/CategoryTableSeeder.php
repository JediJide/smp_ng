<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Scientific Narrative',
                'therapy_area_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Scientific Platform',
                'therapy_area_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Lexicon',
                'therapy_area_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Glossary',
                'therapy_area_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Reference List',
                'therapy_area_id' => 1,
            ],
        ];

        Category::insert($categories);
    }
}
