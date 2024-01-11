<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 9; $i++) { 
            \App\Models\Vehicle::create([
                'name' => 'Lexas',
                'plate_number' => 'plate_number',
                'chesis_number' => 'chesis_number',
                'type' => 'type',
                'model' => 'model',
                'uuid' => \Str::uuid(),
                'image' => 'image',
            ]);
        }
    }
}
