<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use App\Models\District;
use App\Models\Property;
use App\Models\Province;
use App\Models\RoomType;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->name();
        $description = $this->faker->text();

        $users = User::pluck('id');
        $user_id = $this->faker->randomElement($users);

        $property_type = PropertyType::pluck('id');
        $property_type_id = $this->faker->randomElement($property_type);

        $room_type = RoomType::pluck('id');
        $room_type_id = $this->faker->randomElement($room_type);

        $category = Category::pluck('id');
        $category_id = $this->faker->randomElement($category);

        $pronvince = Province::pluck('code');
        $provinces_id = 79;

        $district = District::where('province_code', 79)->pluck('code');
        $districts_id = $this->faker->randomElement($district);


        $address = $this->faker->address();
        $bedroom_count = $this->faker->numberBetween(1, 5);
        $bed_count = $this->faker->numberBetween(1, 5);
        $bathroom_count = $this->faker->numberBetween(1, 5);
        $start_date =  $this->faker->dateTimeThisYear();
        $end_date = $this->faker->dateTimeBetween($start_date, '+10 days');
        $base_price =  $this->faker->numberBetween(200, 1000);
        $minimum_stay = $this->faker->randomDigit();
        $place_greate_for = $this->faker->text();
        $guest_access = $this->faker->text();
        $interaction_guest = $this->faker->text();
        $thing_to_note = $this->faker->text();
        $about_place = $this->faker->text();
        $overview = $this->faker->text();
        $getting_around = $this->faker->text();
        $booking_per = 'day';
        $booking_type = 'review';
        $check_in_after = $this->faker->numberBetween(0, 24);
        $check_out_before = $this->faker->numberBetween(0, 24);
        $cancelation = 'flexible';
        $minimun_stay = $this->faker->randomDigit();
        $maximum_stay = $this->faker->numberBetween($minimun_stay, 10);
        $video = $this->faker->url();
        $property_status = $this->faker->numberBetween(1, 10);

        $acception_status_array = ['accept', 'deny', 'waiting'];
        $acception_status = $this->faker->randomElement($acception_status_array);
        return [
            //
            'name' =>  $name,
            'description' =>  $description,
            'user_id' =>   $user_id,
            'property_type_id' => $property_type_id,
            'room_type_id' =>  $room_type_id,
            'category_id' =>  $category_id,
            'provinces_id' =>    79   /*$this->faker->randomElement($pronvince)*/,
            'districts_id' => $districts_id,
            'address' =>  $address,
            'bedroom_count' =>  $bedroom_count,
            'bed_count' =>  $bed_count,
            'bathroom_count' =>  $bathroom_count,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'base_price' => $base_price,
            'minimum_stay' => $minimum_stay,
            // 'place_greate_for'=> 'place_greate_for',
            'guest_access' => $guest_access,
            'interaction_guest' => $interaction_guest,
            'thing_to_note' => $thing_to_note,
            'about_place' => $about_place,
            'overview' => $overview,
            'getting_around' => $getting_around,
            'booking_per' => $booking_per,
            'booking_type' => $booking_type,
            'check_in_after' => $check_in_after,
            'check_out_before' => $check_out_before,
            'cancelation' => $cancelation,
            'minimun_stay' => $minimun_stay,
            'maximum_stay' => $maximum_stay,
            'video' => $video,
            'property_status' => $property_status,
            'acception_status' => $acception_status
        ];
    }
}
