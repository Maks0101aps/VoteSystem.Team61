<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Petition;
use App\Models\PetitionSignature;
use App\Models\SchoolClass;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetitionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_users_can_view_petitions_list()
    {
        $user = User::factory()->create();
        $petition = Petition::factory()->create([
            'title' => 'Test Petition',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/petitions');

        $response->assertStatus(200);
        $response->assertSee('Test Petition');
    }

    /** @test */
    public function students_can_create_petitions()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $schoolClass = SchoolClass::factory()->create();
        $student->school_class_id = $schoolClass->id;
        $student->save();

        $response = $this->actingAs($student)->post('/petitions', [
            'title' => 'New Petition',
            'description' => 'Petition Description',
            'signatures_required' => 50,
            'duration' => 24,
            'target_type' => 'school',
        ]);

        $response->assertRedirect('/petitions');
        $this->assertDatabaseHas('petitions', [
            'title' => 'New Petition',
            'description' => 'Petition Description',
            'user_id' => $student->id,
            'signatures_required' => 50,
            'duration' => 24,
        ]);
    }

    /** @test */
    public function non_students_cannot_create_petitions()
    {
        $teacher = User::factory()->create([
            'role' => 'teacher',
        ]);

        $response = $this->actingAs($teacher)->post('/petitions', [
            'title' => 'Teacher Petition',
            'description' => 'This should not be allowed',
            'signatures_required' => 50,
            'duration' => 24,
            'target_type' => 'school',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('petitions', [
            'title' => 'Teacher Petition',
        ]);
    }

    /** @test */
    public function students_can_sign_petitions()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $petition = Petition::factory()->create([
            'signatures_required' => 5,
            'status' => 'active',
        ]);

        $response = $this->actingAs($student)->post("/petitions/{$petition->id}/sign");

        $response->assertRedirect();
        $this->assertDatabaseHas('petition_signatures', [
            'user_id' => $student->id,
            'petition_id' => $petition->id,
        ]);
    }

    /** @test */
    public function students_cannot_sign_same_petition_twice()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $petition = Petition::factory()->create([
            'signatures_required' => 5,
            'status' => 'active',
        ]);
        
        // First signature
        PetitionSignature::factory()->create([
            'user_id' => $student->id,
            'petition_id' => $petition->id,
        ]);

        // Try to sign again
        $response = $this->actingAs($student)->post("/petitions/{$petition->id}/sign");
        
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ви вже підписали цю петицію.');
        
        // Verify only one signature exists
        $signatureCount = PetitionSignature::where('user_id', $student->id)
            ->where('petition_id', $petition->id)
            ->count();
        
        $this->assertEquals(1, $signatureCount);
    }

    /** @test */
    public function petition_status_changes_when_required_signatures_reached()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $petition = Petition::factory()->create([
            'signatures_required' => 2,
            'status' => 'active',
        ]);
        
        // First signature
        PetitionSignature::factory()->create([
            'petition_id' => $petition->id,
            'user_id' => User::factory()->create(['role' => 'student'])->id,
        ]);
        
        // Second signature that should trigger status change
        $response = $this->actingAs($student)->post("/petitions/{$petition->id}/sign");
        
        $response->assertRedirect();
        
        // Verify petition status was updated to pending review
        $this->assertDatabaseHas('petitions', [
            'id' => $petition->id,
            'status' => 'pending_review',
        ]);
    }

    /** @test */
    public function author_can_delete_their_petition()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $petition = Petition::factory()->create([
            'user_id' => $student->id,
        ]);

        $response = $this->actingAs($student)->delete("/petitions/{$petition->id}");

        $response->assertRedirect('/petitions');
        $response->assertSessionHas('success', 'Петиція успішно видалена.');
        $this->assertDatabaseMissing('petitions', [
            'id' => $petition->id,
        ]);
    }

    /** @test */
    public function non_author_cannot_delete_petition()
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $petition = Petition::factory()->create([
            'user_id' => $author->id,
        ]);

        $response = $this->actingAs($otherUser)->delete("/petitions/{$petition->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ви не можете видалити цю петицію.');
        $this->assertDatabaseHas('petitions', [
            'id' => $petition->id,
        ]);
    }

    /** @test */
    public function students_can_only_see_relevant_petitions()
    {
        // Create a student in class 10-A
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $class10A = SchoolClass::factory()->create([
            'class_number' => 10,
            'class_letter' => 'A',
        ]);
        
        $class10B = SchoolClass::factory()->create([
            'class_number' => 10,
            'class_letter' => 'B',
        ]);
        
        // Update student's class
        $student->school_class_id = $class10A->id;
        $student->save();
        
        // Create a petition for all school
        $petitionForAll = Petition::factory()->create([
            'title' => 'For All School',
            'school_class_id' => null,
            'status' => 'active',
        ]);
        
        // Create a petition for class 10-A
        $petitionFor10A = Petition::factory()->create([
            'title' => 'For Class 10-A',
            'school_class_id' => $class10A->id,
            'status' => 'active',
        ]);
        
        // Create a petition for class 10-B
        $petitionFor10B = Petition::factory()->create([
            'title' => 'For Class 10-B',
            'school_class_id' => $class10B->id,
            'status' => 'active',
        ]);
        
        $response = $this->actingAs($student)->get('/petitions');
        
        $response->assertStatus(200);
        $response->assertSee('For All School');
        $response->assertSee('For Class 10-A');
        $response->assertDontSee('For Class 10-B');
    }
} 