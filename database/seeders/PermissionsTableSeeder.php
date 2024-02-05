<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'home_edit',
            ],
            [
                'id'    => 18,
                'title' => 'home_access',
            ],
            [
                'id'    => 19,
                'title' => 'application_create',
            ],
            [
                'id'    => 20,
                'title' => 'application_edit',
            ],
            [
                'id'    => 21,
                'title' => 'application_delete',
            ],
            [
                'id'    => 22,
                'title' => 'application_access',
            ],
            [
                'id'    => 23,
                'title' => 'brand_create',
            ],
            [
                'id'    => 24,
                'title' => 'brand_edit',
            ],
            [
                'id'    => 25,
                'title' => 'brand_delete',
            ],
            [
                'id'    => 26,
                'title' => 'brand_access',
            ],
            [
                'id'    => 27,
                'title' => 'industry_create',
            ],
            [
                'id'    => 28,
                'title' => 'industry_edit',
            ],
            [
                'id'    => 29,
                'title' => 'industry_delete',
            ],
            [
                'id'    => 30,
                'title' => 'industry_access',
            ],
            [
                'id'    => 31,
                'title' => 'product_create',
            ],
            [
                'id'    => 32,
                'title' => 'product_edit',
            ],
            [
                'id'    => 33,
                'title' => 'product_delete',
            ],
            [
                'id'    => 34,
                'title' => 'product_access',
            ],
            [
                'id'    => 35,
                'title' => 'reference_create',
            ],
            [
                'id'    => 36,
                'title' => 'reference_edit',
            ],
            [
                'id'    => 37,
                'title' => 'reference_delete',
            ],
            [
                'id'    => 38,
                'title' => 'reference_access',
            ],
            [
                'id'    => 39,
                'title' => 'blog_create',
            ],
            [
                'id'    => 40,
                'title' => 'blog_edit',
            ],
            [
                'id'    => 41,
                'title' => 'blog_delete',
            ],
            [
                'id'    => 42,
                'title' => 'blog_access',
            ],
            [
                'id'    => 43,
                'title' => 'contact_show',
            ],
            [
                'id'    => 44,
                'title' => 'contact_delete',
            ],
            [
                'id'    => 45,
                'title' => 'contact_access',
            ],
            [
                'id'    => 46,
                'title' => 'testimonial_create',
            ],
            [
                'id'    => 47,
                'title' => 'testimonial_edit',
            ],
            [
                'id'    => 48,
                'title' => 'testimonial_delete',
            ],
            [
                'id'    => 49,
                'title' => 'testimonial_access',
            ],
            [
                'id'    => 50,
                'title' => 'translation_center_create',
            ],
            [
                'id'    => 51,
                'title' => 'translation_center_edit',
            ],
            [
                'id'    => 52,
                'title' => 'translation_center_show',
            ],
            [
                'id'    => 53,
                'title' => 'translation_center_delete',
            ],
            [
                'id'    => 54,
                'title' => 'translation_center_access',
            ],
            [
                'id'    => 55,
                'title' => 'gdpr_contact_access',
            ],
            [
                'id'    => 56,
                'title' => 'category_create',
            ],
            [
                'id'    => 57,
                'title' => 'category_edit',
            ],
            [
                'id'    => 58,
                'title' => 'category_delete',
            ],
            [
                'id'    => 59,
                'title' => 'category_access',
            ],
            [
                'id'    => 60,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
