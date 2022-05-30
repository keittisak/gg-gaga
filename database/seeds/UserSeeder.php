<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'admin',
            'username' => 'admin',
            // 'email' => 'admin@email.com',
            'password' =>  \Hash::make('1234')
        ]);
    }
}
