<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => env('TEST_NAME'),
            'email' => env('TEST_EMAIL'),
            'password' => bcrypt( env('TEST_PASSWORD') ),
        ]);
    }
}
