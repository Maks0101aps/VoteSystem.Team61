<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vote;
use App\Models\Voting;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'voting_id' => Voting::factory(),
            'choice' => $this->faker->randomElement(['for', 'against', 'abstain']),
        ];
    }

    /**
     * Configure the vote to be for a specific user
     */
    public function forUser(User $user)
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * Configure the vote to be for a specific voting
     */
    public function forVoting(Voting $voting)
    {
        return $this->state(function (array $attributes) use ($voting) {
            return [
                'voting_id' => $voting->id,
            ];
        });
    }
} 