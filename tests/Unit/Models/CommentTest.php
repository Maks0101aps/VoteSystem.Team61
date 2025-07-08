<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Petition;
use App\Models\User;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Проверяем, существует ли таблица petitions в тестовой БД
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $comment = new Comment;
        
        $this->assertEquals([
            'user_id',
            'commentable_id', 
            'commentable_type',
            'content',
        ], $comment->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();
        
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => get_class($petition),
            'content' => 'Test comment',
        ]);
        
        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    /** @test */
    public function it_belongs_to_petition_commentable()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->active()->create();
        
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => get_class($petition),
            'content' => 'Test comment for petition',
        ]);
        
        $this->assertInstanceOf(Petition::class, $comment->commentable);
        $this->assertEquals($petition->id, $comment->commentable->id);
    }

    /** @test */
    public function it_belongs_to_voting_commentable()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create();
        
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $voting->id,
            'commentable_type' => get_class($voting),
            'content' => 'Test comment for voting',
        ]);
        
        $this->assertInstanceOf(Voting::class, $comment->commentable);
        $this->assertEquals($voting->id, $comment->commentable->id);
    }

    /** @test */
    public function it_can_format_created_at()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->rejected()->create();
        
        // Create a comment with a specific timestamp
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => get_class($petition),
            'content' => 'Test comment with timestamp',
            'created_at' => '2025-07-15 14:30:00',
        ]);
        
        // Refresh the model to ensure the timestamp is properly set
        $comment->refresh();
        
        // Check formatting functions
        $this->assertIsString($comment->created_at_formatted);
        $this->assertIsString($comment->created_at_human);
    }

    /** @test */
    public function it_can_be_filtered_by_commentable_type()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();
        $voting = Voting::factory()->create();
        
        // Create comments for petition and voting
        $petitionComment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => get_class($petition),
            'content' => 'Petition comment',
        ]);
        
        $votingComment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $voting->id,
            'commentable_type' => get_class($voting),
            'content' => 'Voting comment',
        ]);
        
        // Test scopes
        $this->assertEquals(1, Comment::forPetitions()->count());
        $this->assertEquals(1, Comment::forVotings()->count());
        
        $petitionComments = Comment::forPetitions()->get();
        $votingComments = Comment::forVotings()->get();
        
        $this->assertEquals($petitionComment->id, $petitionComments->first()->id);
        $this->assertEquals($votingComment->id, $votingComments->first()->id);
    }
} 