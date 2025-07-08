<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteOption;
use App\Models\Voting;
use App\Models\VotingVisibility;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $voting = new Voting();
        $fillable = [
            'title',
            'description',
            'user_id',
            'ends_at',
        ];

        $this->assertEquals($fillable, $voting->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $voting->user);
        $this->assertEquals($user->id, $voting->user->id);
    }

    /** @test */
    public function it_has_many_vote_options()
    {
        $voting = Voting::factory()->create();
        $voteOption = VoteOption::factory()->create(['voting_id' => $voting->id]);

        $this->assertInstanceOf(VoteOption::class, $voting->voteOptions->first());
        $this->assertEquals(1, $voting->voteOptions->count());
    }

    /** @test */
    public function it_has_many_votes()
    {
        $voting = Voting::factory()->create();
        $user = User::factory()->create();
        $vote = Vote::factory()->create([
            'voting_id' => $voting->id,
            'user_id' => $user->id,
            'choice' => 'for',
        ]);

        $this->assertInstanceOf(Vote::class, $voting->votes->first());
        $this->assertEquals(1, $voting->votes->count());
    }

    /** @test */
    public function it_has_many_visibilities()
    {
        $voting = Voting::factory()->create();
        $visibility = VotingVisibility::factory()->create(['voting_id' => $voting->id]);

        $this->assertInstanceOf(VotingVisibility::class, $voting->visibilities->first());
        $this->assertEquals(1, $voting->visibilities->count());
    }

    /** @test */
    public function it_has_many_comments()
    {
        $voting = Voting::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_id' => $voting->id,
            'commentable_type' => Voting::class
        ]);

        $this->assertInstanceOf(Comment::class, $voting->comments->first());
        $this->assertEquals(1, $voting->comments->count());
    }

    /** @test */
    public function it_can_determine_if_voting_is_active()
    {
        $voting = Voting::factory()->create([
            'ends_at' => Carbon::now()->addDays(5)
        ]);
        
        // Предполагаем наличие метода isActive
        $this->assertTrue($voting->isActive());
        
        $endedVoting = Voting::factory()->create([
            'ends_at' => Carbon::now()->subDays(1)
        ]);
        
        $this->assertFalse($endedVoting->isActive());
    }

    /** @test */
    public function it_can_determine_if_user_voted()
    {
        $voting = Voting::factory()->create();
        $user = User::factory()->create();
        
        // Предполагаем наличие метода hasUserVoted
        $this->assertFalse($voting->hasUserVoted($user));
        
        Vote::factory()->create([
            'voting_id' => $voting->id,
            'user_id' => $user->id,
            'choice' => 'for',
        ]);
        
        $this->assertTrue($voting->fresh()->hasUserVoted($user));
    }

    /** @test */
    public function it_can_count_votes()
    {
        $voting = Voting::factory()->create();
        $this->assertEquals(0, $voting->votes()->count());
        
        for ($i = 0; $i < 5; $i++) {
            Vote::factory()->create([
                'voting_id' => $voting->id,
                'user_id' => User::factory()->create()->id,
                'choice' => 'for',
            ]);
        }
        
        $this->assertEquals(5, $voting->fresh()->votes()->count());
    }
} 