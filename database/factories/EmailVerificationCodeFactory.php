<?php

namespace Database\Factories;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailVerificationCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailVerificationCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'code' => sprintf('%06d', $this->faker->numberBetween(0, 999999)),
            'expires_at' => Carbon::now()->addMinutes(15),
        ];
    }

    /**
     * Configure the verification code to be expired
     */
    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => Carbon::now()->subMinutes(30),
            ];
        });
    }

    /**
     * Configure the verification code for a specific user
     */
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }
} 