<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Petition;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $commentable = $this->faker->randomElement([
            Petition::class,
            Voting::class,
        ]);
        
        return [
            'content' => $this->faker->paragraphs(2, true),
            'user_id' => User::factory(),
            'commentable_type' => $commentable,
            'commentable_id' => $commentable::factory(),
        ];
    }

    /**
     * Configure the comment to be for a petition.
     *
     * @param Petition|null $petition
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forPetition(?Petition $petition = null)
    {
        return $this->state(function (array $attributes) use ($petition) {
            return [
                'commentable_type' => Petition::class,
                'commentable_id' => $petition ? $petition->id : Petition::factory(),
            ];
        });
    }

    /**
     * Configure the comment to be for a voting.
     *
     * @param Voting|null $voting
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forVoting(?Voting $voting = null)
    {
        return $this->state(function (array $attributes) use ($voting) {
            return [
                'commentable_type' => Voting::class,
                'commentable_id' => $voting ? $voting->id : Voting::factory(),
            ];
        });
    }
} 