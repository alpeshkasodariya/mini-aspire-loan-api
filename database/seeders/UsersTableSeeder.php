<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Str;
use Hash;

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
            'name' => Str::random(5),
            'email' => Str::random(5).'@admin.com',
            'password' => Hash::make('admin123'),
            'type'=>'Admin'
        ]);
    }
}
