<?php

namespace Database\Factories;

use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetitionSignatureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PetitionSignature::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'petition_id' => Petition::factory(),
            'user_id' => User::factory(),
        ];
    }
} 