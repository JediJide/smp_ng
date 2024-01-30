<?php

namespace Database\Seeders;

use App\Models\StatementStatus;
use Illuminate\Database\Seeder;

class StatementStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'id' => 1,
                'status' => 'low',
            ],
            [
                'id' => 2,
                'status' => 'medium',
            ],
            [
                'id' => 3,
                'status' => 'high',
            ],

        ];

        StatementStatus::insert($statuses);
    }
}
