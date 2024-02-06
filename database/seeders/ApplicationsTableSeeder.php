<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationsTableSeeder extends Seeder
{
    public function run()
    {
        $applications = DB::statement("
            DELETE FROM minexq.applications;
            ALTER TABLE minexq.applications AUTO_INCREMENT = 1;
        ");

        Role::insert($applications);
    }
}
