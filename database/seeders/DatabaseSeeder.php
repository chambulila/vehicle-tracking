<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory(10)->create([
        //     'name' => 'Lexas',
        //     'plate_number' => 'plate_number',
        //     'chesis_number' => 'chesis_number',
        //     'type' => 'type',
        //     'model' => 'model',
        //     'uuid' => uniqid(),
        // ]);
        $this->call(UserSeeder::class);
        $this->call(VehicleSeeder::class);
    }
}
