<?php

return [

    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,

    ],

    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'user_permissions',
        'model_has_roles' => 'user_roles',
        'role_has_permissions' => 'role_permissions',
    ],

    'column_names' => [
        'role_pivot_key' => null, //default 'role_id',
        'permission_pivot_key' => null, //default 'permission_id',
        'model_morph_key' => 'user_id',
        'team_foreign_key' => 'team_id',
    ],
    'register_permission_check_method' => true,
    'teams' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,

    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];
