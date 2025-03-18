<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeederForPAGES extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id' => 63,
                'title' => 'page_create',
            ],
            [
                'id' => 64,
                'title' => 'page_edit',
            ],
            [
                'id' => 65,
                'title' => 'page_delete',
            ],
            [
                'id' => 66,
                'title' => 'page_access',
            ],
        ];
        
        Permission::insert($permissions);
    }
}
