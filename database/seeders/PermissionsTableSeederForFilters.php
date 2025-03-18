<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeederForFilters extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id' => 67,
                'title' => 'filter_create',
            ],
            [
                'id' => 68,
                'title' => 'filter_edit',
            ],
            [
                'id' => 69,
                'title' => 'filter_delete',
            ],
            [
                'id' => 70,
                'title' => 'filter_access',
            ],
        ];
        
        Permission::insert($permissions);
    }
}
