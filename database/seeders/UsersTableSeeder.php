<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 3,
                'name'           => 'stefanicu',
                'email'          => 'stefanicu@gmail.com',
                'password'       => bcrypt('sarutoby'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
