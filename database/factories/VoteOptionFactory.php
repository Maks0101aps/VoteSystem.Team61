<?php

namespace Database\Factories;

use App\Models\VoteOption;
use App\Models\Voting;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VoteOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voting_id' => Voting::factory(),
            'title' => $this->faker->sentence(3),
        ];
    }
    
    /**
     * Configure the vote option to belong to a specific voting
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