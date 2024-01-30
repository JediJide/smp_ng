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
                'title' => 'statment_management_access',
            ],
            [
                'id'    => 18,
                'title' => 'therapy_area_create',
            ],
            [
                'id'    => 19,
                'title' => 'therapy_area_edit',
            ],
            [
                'id'    => 20,
                'title' => 'therapy_area_show',
            ],
            [
                'id'    => 21,
                'title' => 'therapy_area_delete',
            ],
            [
                'id'    => 22,
                'title' => 'therapy_area_access',
            ],
            [
                'id'    => 23,
                'title' => 'category_create',
            ],
            [
                'id'    => 24,
                'title' => 'category_edit',
            ],
            [
                'id'    => 25,
                'title' => 'category_show',
            ],
            [
                'id'    => 26,
                'title' => 'category_delete',
            ],
            [
                'id'    => 27,
                'title' => 'category_access',
            ],
            [
                'id'    => 28,
                'title' => 'theme_create',
            ],
            [
                'id'    => 29,
                'title' => 'theme_edit',
            ],
            [
                'id'    => 30,
                'title' => 'theme_show',
            ],
            [
                'id'    => 31,
                'title' => 'theme_delete',
            ],
            [
                'id'    => 32,
                'title' => 'theme_access',
            ],
            [
                'id'    => 33,
                'title' => 'resource_create',
            ],
            [
                'id'    => 34,
                'title' => 'resource_edit',
            ],
            [
                'id'    => 35,
                'title' => 'resource_show',
            ],
            [
                'id'    => 36,
                'title' => 'resource_delete',
            ],
            [
                'id'    => 37,
                'title' => 'resource_access',
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
                'title' => 'statement_create',
            ],
            [
                'id'    => 44,
                'title' => 'statement_edit',
            ],
            [
                'id'    => 45,
                'title' => 'statement_show',
            ],
            [
                'id'    => 46,
                'title' => 'statement_delete',
            ],
            [
                'id'    => 47,
                'title' => 'statement_access',
            ],
            [
                'id'    => 48,
                'title' => 'glossary_create',
            ],
            [
                'id'    => 49,
                'title' => 'glossary_edit',
            ],
            [
                'id'    => 50,
                'title' => 'glossary_show',
            ],
            [
                'id'    => 51,
                'title' => 'glossary_delete',
            ],
            [
                'id'    => 52,
                'title' => 'glossary_access',
            ],
            [
                'id'    => 53,
                'title' => 'lexicon_create',
            ],
            [
                'id'    => 54,
                'title' => 'lexicon_edit',
            ],
            [
                'id'    => 55,
                'title' => 'lexicon_show',
            ],
            [
                'id'    => 56,
                'title' => 'lexicon_delete',
            ],
            [
                'id'    => 57,
                'title' => 'lexicon_access',
            ],
            [
                'id'    => 58,
                'title' => 'statement_status_create',
            ],
            [
                'id'    => 59,
                'title' => 'statement_status_edit',
            ],
            [
                'id'    => 60,
                'title' => 'statement_status_show',
            ],
            [
                'id'    => 61,
                'title' => 'statement_status_delete',
            ],
            [
                'id'    => 62,
                'title' => 'statement_status_access',
            ],
            [
                'id'    => 63,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 64,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 65,
                'title' => 'profile_password_edit',
            ],
            [
                'id'    => 66,
                'title' => 'audience_create',
            ],
            [
                'id'    => 67,
                'title' => 'audience_edit',
            ],
            [
                'id'    => 68,
                'title' => 'audience_show',
            ],
            [
                'id'    => 69,
                'title' => 'audience_delete',
            ],
            [
                'id'    => 70,
                'title' => 'audience_access',
            ],

        ];

        Permission::insert($permissions);
    }
}
