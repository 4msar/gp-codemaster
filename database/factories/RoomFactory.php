<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        $types = ['Single Bed', 'Double Bed', 'AC Single Bed', 'AC Double Bed'];
    	return [
    	    'room_number' => rand(100, 999), 
            'price' => rand(1000, 5000), 
            'locked' => 0, 
            'max_persons' => rand(1, 10), 
            'room_type' => $types[rand(0, 3)]
    	];
    }
}
