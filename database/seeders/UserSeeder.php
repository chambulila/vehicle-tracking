<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'fname' => 'admin',
            'lname' => 'chambulila',
            'location' => 'mabibo',
            'phone' => '0744320059',
            'password' => \Hash::make('1234567890'),
            'email' => 'admin@gmail.com',
            'roleId' => 1,
        ]);
    }
}
