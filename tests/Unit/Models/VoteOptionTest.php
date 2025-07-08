<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Vote;
use App\Models\VoteOption;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteOptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $voteOption = new VoteOption();
        $fillable = [
            'voting_id',
            'title',
        ];

        $this->assertEquals($fillable, $voteOption->getFillable());
    }

    /** @test */
    public function it_belongs_to_voting()
    {
        $voting = Voting::factory()->create();
        $voteOption = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'Option 1',
        ]);

        $this->assertEquals($voting->id, $voteOption->voting->id);
    }

    /** @test */
    public function it_has_many_votes()
    {
        $voting = Voting::factory()->create();
        $voteOption = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'for',
        ]);
        
        $vote = Vote::factory()->create([
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);

        $this->assertEquals(1, $voteOption->votes()->count());
    }

    /** @test */
    public function it_can_count_votes()
    {
        $voting = Voting::factory()->create();
        $voteOption = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'for',
        ]);
        
        Vote::factory()->count(3)->create([
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);

        $this->assertEquals(3, $voteOption->countVotes());
    }

    /** @test */
    public function it_can_calculate_vote_percentage()
    {
        $voting = Voting::factory()->create();
        $option1 = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'for',
        ]);
        
        $option2 = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'against',
        ]);
        
        Vote::factory()->count(3)->create([
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);
        
        Vote::factory()->count(1)->create([
            'voting_id' => $voting->id,
            'choice' => 'against',
        ]);

        // 3 out of 4 votes, 75%
        $this->assertEquals(75.0, $option1->calculatePercentage());
        
        // 1 out of 4 votes, 25%
        $this->assertEquals(25.0, $option2->calculatePercentage());
    }

    /** @test */
    public function it_handles_zero_votes_percentage_gracefully()
    {
        $voting = Voting::factory()->create();
        $voteOption = VoteOption::factory()->create([
            'voting_id' => $voting->id,
        ]);

        $this->assertEquals(0, $voteOption->calculatePercentage());
    }
} 