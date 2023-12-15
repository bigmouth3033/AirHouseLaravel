<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Property::factory()->count(2)->create();

        // $properties = Property::pluck('id');
        // foreach ($properties as $property) {
        //     for ($i = 0; $i < 4; $i++) {
        //         # code...
        //         PropertyImage::factory()->state([
        //             'property_id' => $property
        //         ])->create();
        //     }
        // }

        // $property = 95;

        // for ($i = 0; $i < 5; $i++) {
        //     PropertyImage::factory()->state([
        //         'property_id' => $property
        //     ])->create();
        // }
    }
}
