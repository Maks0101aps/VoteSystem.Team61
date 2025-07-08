<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Petition;
use App\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function directors_can_view_pending_petitions()
    {
        $director = User::factory()->create(['role' => 'director']);
        
        $petition = Petition::factory()->create([
            'title' => 'Pending Petition',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($director)->get('/director/petitions');

        $response->assertStatus(200);
        $response->assertSee('Pending Petition');
    }

    /** @test */
    public function non_directors_cannot_access_director_pages()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/director/petitions');

        $response->assertStatus(403);
    }

    /** @test */
    public function directors_can_approve_petitions()
    {
        $director = User::factory()->create(['role' => 'director']);
        
        $petition = Petition::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($director)->post("/director/petitions/{$petition->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Petition approved.');
        
        $this->assertDatabaseHas('petitions', [
            'id' => $petition->id,
            'status' => 'approved',
        ]);
    }

    /** @test */
    public function directors_can_reject_petitions()
    {
        $director = User::factory()->create(['role' => 'director']);
        
        $petition = Petition::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($director)->post("/director/petitions/{$petition->id}/reject");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Petition rejected.');
        
        $this->assertDatabaseHas('petitions', [
            'id' => $petition->id,
            'status' => 'rejected',
        ]);
    }

    /** @test */
    public function petitions_show_with_creator_and_class_information()
    {
        $director = User::factory()->create(['role' => 'director']);
        
        $class = SchoolClass::factory()->create();
        $student = User::factory()->create([
            'role' => 'student',
            'school_class_id' => $class->id,
        ]);
        
        $petition = Petition::factory()->create([
            'status' => 'pending',
            'user_id' => $student->id,
            'school_class_id' => $class->id,
        ]);

        $response = $this->actingAs($director)->get('/director/petitions');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Director/Petitions/Index')
            ->has('petitions', 1)
            ->where('petitions.0.user.id', $student->id)
            ->where('petitions.0.school_class.id', $class->id)
        );
    }
} 