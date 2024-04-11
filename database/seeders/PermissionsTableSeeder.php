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
                'title' => 'application_create',
            ],
            [
                'id'    => 18,
                'title' => 'application_edit',
            ],
            [
                'id'    => 19,
                'title' => 'application_delete',
            ],
            [
                'id'    => 20,
                'title' => 'application_access',
            ],
            [
                'id'    => 21,
                'title' => 'brand_create',
            ],
            [
                'id'    => 22,
                'title' => 'brand_edit',
            ],
            [
                'id'    => 23,
                'title' => 'brand_delete',
            ],
            [
                'id'    => 24,
                'title' => 'brand_access',
            ],
            [
                'id'    => 25,
                'title' => 'industry_create',
            ],
            [
                'id'    => 26,
                'title' => 'industry_edit',
            ],
            [
                'id'    => 27,
                'title' => 'industry_delete',
            ],
            [
                'id'    => 28,
                'title' => 'industry_access',
            ],
            [
                'id'    => 29,
                'title' => 'product_create',
            ],
            [
                'id'    => 30,
                'title' => 'product_edit',
            ],
            [
                'id'    => 31,
                'title' => 'product_delete',
            ],
            [
                'id'    => 32,
                'title' => 'product_access',
            ],
            [
                'id'    => 33,
                'title' => 'reference_create',
            ],
            [
                'id'    => 34,
                'title' => 'reference_edit',
            ],
            [
                'id'    => 35,
                'title' => 'reference_delete',
            ],
            [
                'id'    => 36,
                'title' => 'reference_access',
            ],
            [
                'id'    => 37,
                'title' => 'blog_create',
            ],
            [
                'id'    => 38,
                'title' => 'blog_edit',
            ],
            [
                'id'    => 39,
                'title' => 'blog_delete',
            ],
            [
                'id'    => 40,
                'title' => 'blog_access',
            ],
            [
                'id'    => 41,
                'title' => 'contact_show',
            ],
            [
                'id'    => 42,
                'title' => 'contact_delete',
            ],
            [
                'id'    => 43,
                'title' => 'contact_access',
            ],
            [
                'id'    => 44,
                'title' => 'testimonial_create',
            ],
            [
                'id'    => 45,
                'title' => 'testimonial_edit',
            ],
            [
                'id'    => 46,
                'title' => 'testimonial_delete',
            ],
            [
                'id'    => 47,
                'title' => 'testimonial_access',
            ],
            [
                'id'    => 48,
                'title' => 'translation_center_edit',
            ],
            [
                'id'    => 49,
                'title' => 'translation_center_access',
            ],
            [
                'id'    => 50,
                'title' => 'gdpr_contact_access',
            ],
            [
                'id'    => 51,
                'title' => 'category_create',
            ],
            [
                'id'    => 52,
                'title' => 'category_edit',
            ],
            [
                'id'    => 53,
                'title' => 'category_delete',
            ],
            [
                'id'    => 54,
                'title' => 'category_access',
            ],
            [
                'id'    => 55,
                'title' => 'front_page_edit',
            ],
            [
                'id'    => 56,
                'title' => 'front_page_access',
            ],
            [
                'id'    => 57,
                'title' => 'fron_test_create',
            ],
            [
                'id'    => 58,
                'title' => 'fron_test_edit',
            ],
            [
                'id'    => 59,
                'title' => 'fron_test_show',
            ],
            [
                'id'    => 60,
                'title' => 'fron_test_delete',
            ],
            [
                'id'    => 61,
                'title' => 'fron_test_access',
            ],
            [
                'id'    => 62,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
