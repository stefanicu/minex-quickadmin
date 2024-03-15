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
                'id'             => 1,
                'name'           => 'stefanicu',
                'email'          => 'stefanicu@gmail.com',
                'password'       => bcrypt('sarutoby'),
                'remember_token' => null,
            ],
            [
                'id'             => 2,
                'name'           => 'kopa',
                'email'          => 'eugen.kovacs@gmail.com',
                'password'       => bcrypt('12345'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
