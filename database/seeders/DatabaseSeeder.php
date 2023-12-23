<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Property;
use Illuminate\Support\Arr;
use App\Models\PropertyImage;
use App\Models\PropertyAmenity;
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

        $properties = Property::pluck('id');
        foreach ($properties as $property) {
            $amenites = Amenity::pluck('id');
            $amenites_array = [];
            foreach ($amenites as $amenity) {
                $amenites_array[] = $amenity;
            }
            $randomUniqueAmenites = Arr::random($amenites_array, fake()->numberbetween(7, 10));
            foreach ($randomUniqueAmenites as $randomUniqueAmenity) {
                PropertyAmenity::factory()->state([
                    'property_id' => $property,
                    'amenity_id' => $randomUniqueAmenity
                ])
                    ->count(1)->create();
            }
        }


        // ///////////////////////////////////////////////////////////////////////////
        // User::factory()->count(100)->create();


        // Property::factory()->count(2000)->create();



        ///////////////////////////////////////////////////////////////////////////

    }
}
