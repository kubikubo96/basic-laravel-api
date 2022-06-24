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
            'id' => (string)Str::uuid(),
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

        for ($i = 0; $i < 5; $i++) {
            $users[] = [
                'id' => (string)Str::uuid(),
                'username' => 'author' . $i,
                'password' => Hash::make('1'),
                'admin' => 1,
                'active' => 1,
                'status' => 0,
                'email' => 'author' . $i . '@gmail.com',
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

        for ($i = 0; $i < 5; $i++) {
            $users[] = [
                'id' => (string)Str::uuid(),
                'username' => 'user' . $i,
                'password' => Hash::make('1'),
                'admin' => 0,
                'active' => 1,
                'status' => 0,
                'email' => 'user' . $i . '@gmail.com',
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
        for ($i = 0; $i < 5; $i++) {
            $posts[] = [
                'user_id' => '383ea73e-91a9-49c6-b2b5-393387138e6b',
                'title' => 'Title ' . $i,
                'content' => 'Content ' . $i,
                'slug' => 'slug-' . $i,
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
                    'title' => $i . '-' . $j,
                    'name' => $i . '-' . $j,
                    'created_at' => null,
                    'updated_at' => null,
                ];
            }
        }
        DB::table('roles')->insert($roles);
        DB::table('permissions')->insert($permissions);
    }
}
