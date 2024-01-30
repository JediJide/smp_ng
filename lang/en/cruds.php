<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'name'                     => 'First name',
            'name_helper'              => ' ',
            'email'                    => 'Email',
            'email_helper'             => ' ',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => ' ',
            'password'                 => 'Password',
            'password_helper'          => ' ',
            'roles'                    => 'Roles',
            'roles_helper'             => ' ',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
            'last_name'                => 'Last Name',
            'last_name_helper'         => ' ',
        ],
    ],
    'statmentManagement' => [
        'title'          => 'Statement Management',
        'title_singular' => 'Statement Management',
    ],
    'therapyArea' => [
        'title'          => 'Therapy Area',
        'title_singular' => 'Therapy Area',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'category' => [
        'title'          => 'Category',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'name'                => 'Name',
            'name_helper'         => ' ',
            'therapy_area'        => 'Therapy Area',
            'therapy_area_helper' => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
        ],
    ],
    'theme' => [
        'title'          => 'Theme',
        'title_singular' => 'Theme',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'therapy_area'        => 'Therapy Area',
            'therapy_area_helper' => ' ',
            'category'            => 'Category',
            'category_helper'     => ' ',
            'name'                => 'Name',
            'name_helper'         => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
            'resource'            => 'Resource',
            'resource_helper'     => ' ',
            'reference'           => 'Reference',
            'reference_helper'    => ' ',
        ],
    ],
    'resource' => [
        'title'          => 'Resources',
        'title_singular' => 'Resource',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'title'                => 'Title',
            'title_helper'         => ' ',
            'filename'             => 'File name',
            'filename_helper'      => ' ',
            'user'                 => 'User',
            'user_helper'          => ' ',
            'url'                  => 'Url',
            'url_helper'           => ' ',
            'temporary_url'        => 'Temporary Url',
            'temporary_url_helper' => ' ',
            'ip_address'           => 'Ip Address',
            'ip_address_helper'    => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
        ],
    ],
    'reference' => [
        'title'          => 'Reference',
        'title_singular' => 'Reference',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'url'               => 'Url',
            'url_helper'        => ' ',
            'tag'               => 'Tag',
            'tag_helper'        => ' ',
            'file'              => 'File',
            'file_helper'       => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'statement' => [
        'title'          => 'Statement',
        'title_singular' => 'Statement',
        'fields'         => [
            'id'                   => 'ID',
            'id_helper'            => ' ',
            'title'                => 'Title',
            'title_helper'         => ' ',
            'description'          => 'Description',
            'description_helper'   => ' ',
            'is_notify_all'        => 'Notify Users',
            'is_notify_all_helper' => ' ',
            'theme'                => 'Theme',
            'theme_helper'         => ' ',
            'created_at'           => 'Created at',
            'created_at_helper'    => ' ',
            'updated_at'           => 'Updated at',
            'updated_at_helper'    => ' ',
            'deleted_at'           => 'Deleted at',
            'deleted_at_helper'    => ' ',
            'therapy_area'         => 'Therapy Area',
            'therapy_area_helper'  => ' ',
            'parent'               => 'Parent',
            'parent_helper'        => ' ',
            'resource'             => 'Resource',
            'resource_helper'      => ' ',
            'reference'            => 'Reference',
            'reference_helper'     => ' ',
            'status'               => 'Status',
            'status_helper'        => ' ',
            'order_by'             => 'Order By',
            'order_by_helper'      => ' ',
        ],
    ],
    'glossary' => [
        'title'          => 'Glossary',
        'title_singular' => 'Glossary',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'term'                => 'Term',
            'term_helper'         => ' ',
            'definition'          => 'Definition',
            'definition_helper'   => ' ',
            'therapy_area'        => 'Therapy Area',
            'therapy_area_helper' => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
        ],
    ],
    'lexicon' => [
        'title'          => 'Lexicon',
        'title_singular' => 'Lexicon',
        'fields'         => [
            'id'                         => 'ID',
            'id_helper'                  => ' ',
            'therapy_area'               => 'Therapy Area',
            'therapy_area_helper'        => ' ',
            'preferred_phrase'           => 'Preferred Terms/Phrase',
            'preferred_phrase_helper'    => ' ',
            'guidance_for_usage'         => 'Guidance For Usage',
            'guidance_for_usage_helper'  => ' ',
            'non_preferred_terms'        => 'Non Preferred Terms/Phrases',
            'non_preferred_terms_helper' => ' ',
            'created_at'                 => 'Created at',
            'created_at_helper'          => ' ',
            'updated_at'                 => 'Updated at',
            'updated_at_helper'          => ' ',
            'deleted_at'                 => 'Deleted at',
            'deleted_at_helper'          => ' ',
        ],
    ],
    'statementStatus' => [
        'title'          => 'Statement Status',
        'title_singular' => 'Statement Status',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'status'            => 'Status',
            'status_helper'     => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
];
