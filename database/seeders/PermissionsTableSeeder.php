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
                'title' => 'home_show',
            ],
            [
                'id'    => 19,
                'title' => 'home_access',
            ],
            [
                'id'    => 20,
                'title' => 'application_create',
            ],
            [
                'id'    => 21,
                'title' => 'application_edit',
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
                'title' => 'brand_show',
            ],
            [
                'id'    => 26,
                'title' => 'brand_delete',
            ],
            [
                'id'    => 27,
                'title' => 'brand_access',
            ],
            [
                'id'    => 28,
                'title' => 'industry_create',
            ],
            [
                'id'    => 29,
                'title' => 'industry_edit',
            ],
            [
                'id'    => 30,
                'title' => 'industry_show',
            ],
            [
                'id'    => 31,
                'title' => 'industry_delete',
            ],
            [
                'id'    => 32,
                'title' => 'industry_access',
            ],
            [
                'id'    => 33,
                'title' => 'product_create',
            ],
            [
                'id'    => 34,
                'title' => 'product_edit',
            ],
            [
                'id'    => 35,
                'title' => 'product_show',
            ],
            [
                'id'    => 36,
                'title' => 'product_delete',
            ],
            [
                'id'    => 37,
                'title' => 'product_access',
            ],
            [
                'id'    => 38,
                'title' => 'reference_create',
            ],
            [
                'id'    => 39,
                'title' => 'reference_edit',
            ],
            [
                'id'    => 40,
                'title' => 'reference_show',
            ],
            [
                'id'    => 41,
                'title' => 'reference_delete',
            ],
            [
                'id'    => 42,
                'title' => 'reference_access',
            ],
            [
                'id'    => 43,
                'title' => 'blog_create',
            ],
            [
                'id'    => 44,
                'title' => 'blog_edit',
            ],
            [
                'id'    => 45,
                'title' => 'blog_show',
            ],
            [
                'id'    => 46,
                'title' => 'blog_delete',
            ],
            [
                'id'    => 47,
                'title' => 'blog_access',
            ],
            [
                'id'    => 48,
                'title' => 'contact_show',
            ],
            [
                'id'    => 49,
                'title' => 'contact_delete',
            ],
            [
                'id'    => 50,
                'title' => 'contact_access',
            ],
            [
                'id'    => 51,
                'title' => 'testimonial_create',
            ],
            [
                'id'    => 52,
                'title' => 'testimonial_edit',
            ],
            [
                'id'    => 53,
                'title' => 'testimonial_show',
            ],
            [
                'id'    => 54,
                'title' => 'testimonial_delete',
            ],
            [
                'id'    => 55,
                'title' => 'testimonial_access',
            ],
            [
                'id'    => 56,
                'title' => 'translation_center_create',
            ],
            [
                'id'    => 57,
                'title' => 'translation_center_edit',
            ],
            [
                'id'    => 58,
                'title' => 'translation_center_show',
            ],
            [
                'id'    => 59,
                'title' => 'translation_center_delete',
            ],
            [
                'id'    => 60,
                'title' => 'translation_center_access',
            ],
            [
                'id'    => 61,
                'title' => 'gdpr_contact_access',
            ],
            [
                'id'    => 62,
                'title' => 'category_create',
            ],
            [
                'id'    => 63,
                'title' => 'category_edit',
            ],
            [
                'id'    => 64,
                'title' => 'category_delete',
            ],
            [
                'id'    => 65,
                'title' => 'category_access',
            ],
            [
                'id'    => 66,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
