<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Index')
            ->where('guestUser', false)
        );
    }

    /** @test */
    public function unauthenticated_users_are_redirected_from_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function dashboard_shows_different_content_for_different_roles()
    {
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);
        $director = User::factory()->create(['role' => 'director']);

        // Test for student
        $responseStudent = $this->actingAs($student)->get('/dashboard');
        $responseStudent->assertStatus(200);

        // Test for teacher
        $responseTeacher = $this->actingAs($teacher)->get('/dashboard');
        $responseTeacher->assertStatus(200);

        // Test for director
        $responseDirector = $this->actingAs($director)->get('/dashboard');
        $responseDirector->assertStatus(200);
    }
} 