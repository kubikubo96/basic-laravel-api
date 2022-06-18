<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Chia 4 lần để chạy
     *
     * @return void
     */
    public function run()
    {
        $users[] = [
            'id' => (string) Str::uuid(),
            'username' => 'supperadmin',
            'password' => Hash::make('1'),
            'admin' => 1,
            'active' => 1,
            'status' => 0,
            'email' => 'supperadmin@gmail.com',
            'email_verified_time' => null,
            'phone_number' => '0999999999',
            'phone_number_verified_time' => null,
            'first_name' => 'Supper',
            'last_name' => 'Admin',
            'remember_token' => null,
            'created_at' => null,
            'updated_at' => null,
        ];

        $users[] = [
            'id' => '383ea73e-91a9-49c6-b2b5-393387138e6b',
            'username' => 'admin',
            'password' => Hash::make('1'),
            'admin' => 1,
            'active' => 1,
            'status' => 0,
            'email' => 'admin@gmail.com',
            'email_verified_time' => null,
            'phone_number' => '0888888888',
            'phone_number_verified_time' => null,
            'first_name' => 'Nguyễn Tất',
            'last_name' => 'Tiến',
            'remember_token' => null,
            'created_at' => null,
            'updated_at' => null,
        ];

        $users[] = [
            'id' => '08a02762-e439-40cf-a158-709ee08d51f4',
            'username' => 'author',
            'password' => Hash::make('1'),
            'admin' => 1,
            'active' => 1,
            'status' => 0,
            'email' => 'author@gmail.com',
            'email_verified_time' => null,
            'phone_number' => '0777777777',
            'phone_number_verified_time' => null,
            'first_name' => 'Auth',
            'last_name' => 'X',
            'remember_token' => null,
            'created_at' => null,
            'updated_at' => null,
        ];

        for ($i = 0; $i < 5; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'username' => 'author'.$i,
                'password' => Hash::make('1'),
                'admin' => 1,
                'active' => 1,
                'status' => 0,
                'email' => 'author'.$i.'@gmail.com',
                'email_verified_time' => null,
                'phone_number' => null,
                'phone_number_verified_time' => null,
                'first_name' => 'Author',
                'last_name' => $i,
                'remember_token' => null,
                'created_at' => null,
                'updated_at' => null,
            ];
        }

        for ($i = 1; $i < 10; $i++) {
            $users[] = [
                'id' => (string) Str::uuid(),
                'username' => 'user'.$i,
                'password' => Hash::make('1'),
                'admin' => 0,
                'active' => 1,
                'status' => 0,
                'email' => 'user'.$i.'@gmail.com',
                'email_verified_time' => null,
                'phone_number' => null,
                'phone_number_verified_time' => null,
                'first_name' => 'User',
                'last_name' => $i,
                'remember_token' => null,
                'created_at' => null,
                'updated_at' => null,
            ];
        }
        DB::table('users')->insert($users);

        $posts = [];
        for ($i = 1; $i < 10; $i++) {
            $posts[] = [
                'user_id' => '383ea73e-91a9-49c6-b2b5-393387138e6b',
                'title' => 'Title '.$i,
                'content' => 'Content '.$i,
                'slug' => 'slug-'.$i,
                'active' => 1,
                'status' => 0,
                'created_at' => null,
                'updated_at' => null,
            ];
        }
        DB::table('posts')->insert($posts);

        $roles = [
            [
                'title' => 'Manager users',
                'name' => 'manager-users',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'title' => 'Manager posts',
                'name' => 'manager-posts',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'title' => 'Manager RPAC',
                'name' => 'manager-rpac',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'title' => 'Manager comments',
                'name' => 'manager-comments',
                'created_at' => null,
                'updated_at' => null,
            ]
        ];
        $a = ['list', 'create', 'update', 'delete'];
        $b = ['role', 'permission', 'user', 'post', 'comment'];
        $permissions = [];
        foreach ($a as $i) {
            foreach ($b as $j) {
                $permissions[] = [
                    'title' => $i.'-'.$j,
                    'name' => $i.'-'.$j,
                    'created_at' => null,
                    'updated_at' => null,
                ];
            }
        }

        $role_permissions = [
            ['role_id' => 1, 'permission_id' => 3],
            ['role_id' => 1, 'permission_id' => 8],
            ['role_id' => 1, 'permission_id' => 18],

            ['role_id' => 2, 'permission_id' => 4],
            ['role_id' => 2, 'permission_id' => 9],
            ['role_id' => 2, 'permission_id' => 19],
            ['role_id' => 2, 'permission_id' => 5],
            ['role_id' => 2, 'permission_id' => 20],

            ['role_id' => 3, 'permission_id' => 1],
            ['role_id' => 3, 'permission_id' => 2],
            ['role_id' => 3, 'permission_id' => 3],
            ['role_id' => 3, 'permission_id' => 6],
            ['role_id' => 3, 'permission_id' => 7],
            ['role_id' => 3, 'permission_id' => 8],
            ['role_id' => 3, 'permission_id' => 11],
            ['role_id' => 3, 'permission_id' => 12],
            ['role_id' => 3, 'permission_id' => 16],
            ['role_id' => 3, 'permission_id' => 17],
            ['role_id' => 3, 'permission_id' => 17],

            ['role_id' => 4, 'permission_id' => 5],
            ['role_id' => 4, 'permission_id' => 10],
            ['role_id' => 4, 'permission_id' => 20],
        ];

        $user_roles = [
            ['user_id' => '383ea73e-91a9-49c6-b2b5-393387138e6b', 'model_type' => 'App\Models\User', 'role_id' => 3],
            ['user_id' => '08a02762-e439-40cf-a158-709ee08d51f4', 'model_type' => 'App\Models\User', 'role_id' => 2],
        ];

        DB::table('roles')->insert($roles);
        DB::table('permissions')->insert($permissions);
        DB::table('role_permissions')->insert($role_permissions);
        DB::table('user_roles')->insert($user_roles);
    }
}
