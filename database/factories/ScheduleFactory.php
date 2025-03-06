<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Room;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition()
    {
        return [
            'subject_id' => Subject::factory(),
            'teacher_id' => User::factory(),
            'group_id' => Group::factory(),
            'room_id' => Room::factory(),
            'pair' => $this->faker->numberBetween(1, 6),
            'week_day' => $this->faker->randomElement(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']),
            'date' => $this->faker->date(),
        ];
    }
}
