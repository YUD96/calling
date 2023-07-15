<?php

namespace Database\Factories;

use App\Enums\PhoneCallStatus;
use App\Models\PhoneCall;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<PhoneCall>
 */
class PhoneCallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'caller_user_id' => User::factory(),
            'receiver_user_id' => User::factory(),
            'status' => Arr::random(PhoneCallStatus::cases()),
            'called_at' => $this->faker->dateTime,
        ];
    }
}
