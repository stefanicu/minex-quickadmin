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
                'password'       => bcrypt('sasuKe132#'),
                'remember_token' => null,
            ],
            [
                'id'             => 2,
                'name'           => 'ionutp',
                'email'          => 'ionut.pitiriga@minex.ro',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 3,
                'name'           => 'mirceam',
                'email'          => 'mircea.minea@minex.ro',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 4,
                'name'           => 'nicusoru',
                'email'          => 'nicusor.ungureanu@minex.ro',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 5,
                'name'           => 'decebalp',
                'email'          => 'decebal.pitiriga@minex.ro',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 6,
                'name'           => 'adrianh',
                'email'          => 'adrian.hentulescu@minex.ro',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 7,
                'name'           => 'emanuelas',
                'email'          => 'emanuela.stambuli@minexgroup.eu',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 8,
                'name'           => 'rasar',
                'email'          => 'razaniene@gmail.com',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 9,
                'name'           => 'ioanar',
                'email'          => 'ioana.roca@minexgroup.eu',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
            [
                'id'             => 10,
                'name'           => 'razvanr',
                'email'          => 'razvan.rotar@minexgroup.eu',
                'password'       => bcrypt('RMqVaBK5T5*5YA'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
