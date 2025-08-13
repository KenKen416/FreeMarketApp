<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $users = [
            ['id' => 1, 'name' => '出品者１', 'email' => '111@test.com', 'password' => 'password'],
            ['id' => 2, 'name' => '出品者２', 'email' => '222@test.com', 'password' => 'password'],
            ['id' => 3, 'name' => '出品者３', 'email' => '333@test.com', 'password' => 'password'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
