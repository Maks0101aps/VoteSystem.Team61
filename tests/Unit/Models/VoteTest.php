<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Vote;
use App\Models\VoteOption;
use App\Models\Voting;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $vote = new Vote();
        $fillable = [
            'user_id',
            'voting_id',
            'choice',
        ];

        $this->assertEquals($fillable, $vote->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create();
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'voting_id' => $voting->id,
        ]);

        $this->assertEquals($user->id, $vote->user->id);
    }

    /** @test */
    public function it_belongs_to_voting()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create();
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'voting_id' => $voting->id,
        ]);

        $this->assertEquals($voting->id, $vote->voting->id);
    }

    /** @test */
    public function it_enforces_unique_user_vote_per_voting()
    {
        $this->expectException(QueryException::class);
        
        $user = User::factory()->create();
        $voting = Voting::factory()->create();
        
        // First vote is fine
        Vote::factory()->create([
            'user_id' => $user->id,
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);
        
        // Second vote should throw an exception due to unique constraint
        Vote::factory()->create([
            'user_id' => $user->id,
            'voting_id' => $voting->id,
            'choice' => 'against',
        ]);
    }
} 