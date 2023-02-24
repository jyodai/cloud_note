<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
          [
            'name' => 'admin',
            'email' => 'admin@exsample.com',
            'password' => Hash::make('admin'),
          ]
        ]);
    }
}
