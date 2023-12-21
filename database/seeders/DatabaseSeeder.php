<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Booking;
use App\Models\User;
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
        //     $property_images = PropertyImage::where('property_id', $property)->first();
        //     if (!$property_images) {
        //         for ($i = 0; $i < 5; $i++) {
        //             PropertyImage::factory()->state([
        //                 'property_id' => $property
        //             ])->create();
        //         }
        //     }
        // }


        // ///////////////////////////////////////////////////////////////////////////
        // User::factory()->count(100)->create();


        // Property::factory()->count(2000)->create();



        ///////////////////////////////////////////////////////////////////////////

        // Booking::factory()->count(3)->state([
        //     'booking_status' => 'deny'
        // ])->create();
    }
}
