<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voting;
use App\Models\VoteOption;
use App\Models\Vote;
use App\Models\SchoolClass;
use App\Models\VotingVisibility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_view_votings_list()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create([
            'title' => 'Test Voting',
        ]);
        
        // Создаем видимость для всех пользователей
        VotingVisibility::factory()->create([
            'voting_id' => $voting->id,
            'role' => 'all',
        ]);

        $response = $this->actingAs($user)->get('/votings');

        $response->assertStatus(200);
        $response->assertSee('Test Voting');
    }

    /** @test */
    public function users_can_create_new_votings()
    {
        $user = User::factory()->create([
            'role' => 'student',
        ]);
        
        $schoolClass = SchoolClass::factory()->create();
        $user->school_class_id = $schoolClass->id;
        $user->save();

        $response = $this->actingAs($user)->post('/votings', [
            'title' => 'New Voting',
            'description' => 'Voting Description',
            'duration' => 60, // 1 час
            'target_type' => 'class',
        ]);

        $response->assertRedirect('/votings');
        $this->assertDatabaseHas('votings', [
            'title' => 'New Voting',
            'description' => 'Voting Description',
            'user_id' => $user->id,
        ]);
        
        $voting = Voting::where('title', 'New Voting')->first();
        
        $this->assertDatabaseHas('voting_visibilities', [
            'voting_id' => $voting->id,
            'role' => 'student',
            'class_number' => $schoolClass->class_number,
            'class_letter' => $schoolClass->class_letter,
        ]);
    }

    /** @test */
    public function users_can_vote_on_active_votings()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create([
            'ends_at' => now()->addDays(1),
        ]);
        
        $option = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'Option 1',
        ]);

        $response = $this->actingAs($user)->post("/votings/{$voting->id}/vote", [
            'choice' => 'for',
        ]);

        $response->assertRedirect('/votings');
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);
    }

    /** @test */
    public function users_cannot_vote_on_expired_votings()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create([
            'ends_at' => now()->subDays(1), // Expired voting
        ]);
        
        $option = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'Option 1',
        ]);

        $response = $this->actingAs($user)->post("/votings/{$voting->id}/vote", [
            'choice' => 'for',
        ]);

        $response->assertRedirect('/votings');
        $response->assertSessionHas('error', 'Voting has ended.');
        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'voting_id' => $voting->id,
        ]);
    }

    /** @test */
    public function users_cannot_vote_twice_on_same_voting()
    {
        $user = User::factory()->create();
        $voting = Voting::factory()->create([
            'ends_at' => now()->addDays(1),
        ]);
        
        $option = VoteOption::factory()->create([
            'voting_id' => $voting->id,
            'title' => 'Option 1',
        ]);
        
        // First vote
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'voting_id' => $voting->id,
            'choice' => 'for',
        ]);

        // Try to vote again
        $response = $this->actingAs($user)->post("/votings/{$voting->id}/vote", [
            'choice' => 'against',
        ]);
        
        // Проверяем, что запрос вызывает исключение (UniqueConstraintViolationException), 
        // и перенаправляет пользователя на страницу голосований
        $response->assertStatus(500);
        
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'voting_id' => $voting->id,
            'choice' => 'for', // First choice remains
        ]);
        
        // Проверяем, что второго голоса нет
        $voteCount = Vote::where('user_id', $user->id)
            ->where('voting_id', $voting->id)
            ->count();
        $this->assertEquals(1, $voteCount);
    }

    /** @test */
    public function users_can_only_see_votings_for_their_role_and_class()
    {
        // Create a student in class 10-A
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $class10A = SchoolClass::factory()->create([
            'class_number' => 10,
            'class_letter' => 'A',
        ]);
        
        // Update student's class
        $student->school_class_id = $class10A->id;
        $student->save();
        
        // Create a voting visible to all students
        $votingForAllStudents = Voting::factory()->create([
            'title' => 'For All Students',
        ]);
        
        VotingVisibility::factory()->create([
            'voting_id' => $votingForAllStudents->id,
            'role' => 'student',
            'class_number' => null,
            'class_letter' => null,
        ]);
        
        // Create a voting visible only to class 10-A
        $votingFor10A = Voting::factory()->create([
            'title' => 'For Class 10-A',
        ]);
        
        VotingVisibility::factory()->create([
            'voting_id' => $votingFor10A->id,
            'role' => 'student',
            'class_number' => 10,
            'class_letter' => 'A',
        ]);
        
        // Create a voting visible only to class 10-B
        $votingFor10B = Voting::factory()->create([
            'title' => 'For Class 10-B',
        ]);
        
        VotingVisibility::factory()->create([
            'voting_id' => $votingFor10B->id,
            'role' => 'student',
            'class_number' => 10,
            'class_letter' => 'B',
        ]);
        
        // Create a voting visible only to teachers
        $votingForTeachers = Voting::factory()->create([
            'title' => 'For Teachers',
        ]);
        
        VotingVisibility::factory()->create([
            'voting_id' => $votingForTeachers->id,
            'role' => 'teacher',
            'class_number' => null,
            'class_letter' => null,
        ]);
        
        $response = $this->actingAs($student)->get('/votings');
        
        $response->assertStatus(200);
        $response->assertSee('For All Students');
        $response->assertSee('For Class 10-A');
        $response->assertDontSee('For Class 10-B');
        $response->assertDontSee('For Teachers');
    }
} 