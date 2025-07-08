<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Petition;
use App\Models\Voting;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_comment_on_petitions()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();

        $response = $this->actingAs($user)->post("/comments/{$petition->id}", [
            'content' => 'This is a comment on a petition',
            'commentable_type' => Petition::class,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a comment on a petition',
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => Petition::class,
        ]);
    }

    /** @test */
    public function users_can_comment_on_votings()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create();

        $response = $this->actingAs($user)->post("/comments/{$voting->id}", [
            'content' => 'This is a comment on a voting',
            'commentable_type' => Voting::class,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a comment on a voting',
            'user_id' => $user->id,
            'commentable_id' => $voting->id,
            'commentable_type' => Voting::class,
        ]);
    }

    /** @test */
    public function comment_content_is_required()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();

        $response = $this->actingAs($user)->post("/comments/{$petition->id}", [
            'content' => '',
            'commentable_type' => Petition::class,
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => Petition::class,
        ]);
    }

    /** @test */
    public function commentable_type_must_be_valid()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create();

        $response = $this->actingAs($user)->post("/comments/{$petition->id}", [
            'content' => 'This is a comment',
            'commentable_type' => 'App\\Models\\InvalidModel',
        ]);

        $response->assertSessionHasErrors('commentable_type');
        $this->assertDatabaseMissing('comments', [
            'content' => 'This is a comment',
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
        ]);
    }

    /** @test */
    public function guests_cannot_comment()
    {
        $petition = Petition::factory()->create();

        $response = $this->post("/comments/{$petition->id}", [
            'content' => 'This is a comment',
            'commentable_type' => Petition::class,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'content' => 'This is a comment',
            'commentable_id' => $petition->id,
        ]);
    }
} 