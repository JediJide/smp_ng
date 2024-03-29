<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@synaptikdigital.com',
                'password' => bcrypt('password'),
                'remember_token' => null,
                'last_name' => '',
            ],
        ];

        User::insert($users);
    }
}
