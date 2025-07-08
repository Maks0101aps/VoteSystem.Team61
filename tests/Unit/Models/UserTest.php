<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Organization;
use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\UserVote;
use App\Models\Vote;
use App\Models\Voting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Общая проверка для тестов, не связанных с петициями
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $user = new User;
        
        $this->assertEquals([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'password',
            'role',
            'school_class_id',
        ], $user->getFillable());
    }

    /** @test */
    public function it_belongs_to_school_class()
    {
        $schoolClass = SchoolClass::factory()->create();
        $user = User::factory()->create([
            'school_class_id' => $schoolClass->id
        ]);
        
        $this->assertInstanceOf(SchoolClass::class, $user->schoolClass);
        $this->assertEquals($schoolClass->id, $user->schoolClass->id);
    }

    /** @test */
    public function it_has_many_organizations()
    {
        $user = User::factory()->create();
        $org1 = Organization::factory()->create(['user_id' => $user->id]);
        $org2 = Organization::factory()->create(['user_id' => $user->id]);
        
        $this->assertCount(2, $user->organizations);
        $this->assertEquals($org1->id, $user->organizations[0]->id);
        $this->assertEquals($org2->id, $user->organizations[1]->id);
    }

    /** @test */
    public function it_has_many_petitions()
    {
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
        
        $user = User::factory()->create();
        $petition1 = Petition::factory()->create(['user_id' => $user->id]);
        $petition2 = Petition::factory()->create(['user_id' => $user->id]);
        
        $this->assertCount(2, $user->petitions);
        $this->assertEquals($petition1->id, $user->petitions[0]->id);
        $this->assertEquals($petition2->id, $user->petitions[1]->id);
    }

    /** @test */
    public function it_has_many_petition_signatures()
    {
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
        
        $user = User::factory()->create();
        $petition1 = Petition::factory()->create();
        $petition2 = Petition::factory()->create();
        
        $signature1 = $petition1->signatures()->create(['user_id' => $user->id]);
        $signature2 = $petition2->signatures()->create(['user_id' => $user->id]);
        
        $this->assertCount(2, $user->petitionSignatures);
        $this->assertEquals($signature1->id, $user->petitionSignatures[0]->id);
        $this->assertEquals($signature2->id, $user->petitionSignatures[1]->id);
    }

    /** @test */
    public function it_has_many_votings()
    {
        $user = User::factory()->create();
        $voting1 = Voting::factory()->create(['user_id' => $user->id]);
        $voting2 = Voting::factory()->create(['user_id' => $user->id]);
        
        $this->assertCount(2, $user->votings);
        $this->assertEquals($voting1->id, $user->votings[0]->id);
        $this->assertEquals($voting2->id, $user->votings[1]->id);
    }

    /** @test */
    public function it_has_many_user_votes()
    {
        $user = User::factory()->create();
        $voting1 = Voting::factory()->create();
        $voting2 = Voting::factory()->create();
        
        $vote1 = $user->votes()->create([
            'voting_id' => $voting1->id,
            'choice' => 'for'
        ]);
        
        $vote2 = $user->votes()->create([
            'voting_id' => $voting2->id,
            'choice' => 'against'
        ]);
        
        $this->assertCount(2, $user->votes);
        $this->assertEquals($vote1->id, $user->votes[0]->id);
        $this->assertEquals($vote2->id, $user->votes[1]->id);
    }

    /** @test */
    public function it_has_many_comments()
    {
        if (!Schema::hasTable('petitions')) {
            $this->markTestSkipped('Таблица petitions не существует.');
        }
        
        $user = User::factory()->create();
        $petition = Petition::factory()->create();
        $voting = Voting::factory()->create();
        
        $comment1 = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $petition->id,
            'commentable_type' => get_class($petition),
            'content' => 'Comment on petition'
        ]);
        
        $comment2 = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $voting->id,
            'commentable_type' => get_class($voting),
            'content' => 'Comment on voting'
        ]);
        
        $this->assertCount(2, $user->comments);
        $this->assertEquals($comment1->id, $user->comments[0]->id);
        $this->assertEquals($comment2->id, $user->comments[1]->id);
    }

    /** @test */
    public function it_returns_full_name()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'middle_name' => 'Robert',
            'last_name' => 'Doe'
        ]);
        
        $this->assertEquals('John Robert Doe', $user->getFullName());
        
        $userWithoutMiddleName = User::factory()->create([
            'first_name' => 'Jane',
            'middle_name' => null,
            'last_name' => 'Smith'
        ]);
        
        $this->assertEquals('Jane Smith', $userWithoutMiddleName->getFullName());
    }
} 