<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Petition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CheckRoleTest extends TestCase
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
    public function students_cannot_access_director_routes()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $response = $this->actingAs($student)->get('/director/petitions');
        
        $response->assertStatus(403);
    }
    
    /** @test */
    public function directors_can_access_director_routes()
    {
        $director = User::factory()->create([
            'role' => 'director',
        ]);
        
        $response = $this->actingAs($director)->get('/director/petitions');
        
        $response->assertStatus(200);
    }
    
    /** @test */
    public function directors_can_approve_petitions()
    {
        $director = User::factory()->create([
            'role' => 'director',
        ]);
        
        $petition = Petition::factory()->pending()->create();
        
        $response = $this->actingAs($director)
            ->post("/director/petitions/{$petition->id}/approve");
        
        $response->assertRedirect();
        $this->assertEquals('approved', $petition->fresh()->status);
    }
    
    /** @test */
    public function directors_can_reject_petitions()
    {
        $director = User::factory()->create([
            'role' => 'director',
        ]);
        
        $petition = Petition::factory()->pending()->create();
        
        $response = $this->actingAs($director)
            ->post("/director/petitions/{$petition->id}/reject");
        
        $response->assertRedirect();
        $this->assertEquals('rejected', $petition->fresh()->status);
    }
    
    /** @test */
    public function students_cannot_approve_or_reject_petitions()
    {
        $student = User::factory()->create([
            'role' => 'student',
        ]);
        
        $petition = Petition::factory()->pending()->create();
        
        $response = $this->actingAs($student)
            ->post("/director/petitions/{$petition->id}/approve");
        
        $response->assertStatus(403);
        $this->assertEquals('pending', $petition->fresh()->status);
        
        $response = $this->actingAs($student)
            ->post("/director/petitions/{$petition->id}/reject");
        
        $response->assertStatus(403);
        $this->assertEquals('pending', $petition->fresh()->status);
    }
} 