<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Voting;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'user_id' => User::factory(),
            'ends_at' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
        ];
    }

    /**
     * Active voting
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'ends_at' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            ];
        });
    }

    /**
     * Closed voting
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function closed()
    {
        return $this->state(function (array $attributes) {
            return [
                'ends_at' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            ];
        });
    }
} 