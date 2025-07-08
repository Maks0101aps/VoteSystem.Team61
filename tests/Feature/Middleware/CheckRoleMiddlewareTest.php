<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_access_to_users_with_correct_role()
    {
        $director = User::factory()->create(['role' => 'director']);
        
        // Предполагаем, что этот маршрут защищен middleware CheckRole:director
        $response = $this->actingAs($director)->get('/director/petitions');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function it_denies_access_to_users_with_incorrect_role()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        // Предполагаем, что этот маршрут защищен middleware CheckRole:director
        $response = $this->actingAs($student)->get('/director/petitions');
        
        $response->assertStatus(403);
    }

    /** @test */
    public function it_denies_access_to_unauthenticated_users()
    {
        // Не аутентифицируем пользователя
        $response = $this->get('/director/petitions');
        
        // Должен быть редирект на страницу логина или ошибка 403
        $response->assertStatus(403);
    }

    /** @test */
    public function it_allows_different_role_access_to_appropriate_routes()
    {
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);
        
        // Предполагаем, что у нас есть маршрут для учителей
        // Создадим такой маршрут для теста
        \Route::get('/test-teacher-route', function () {
            return 'Teacher Route';
        })->middleware('check.role:teacher');
        
        // Студент не должен иметь доступ
        $response = $this->actingAs($student)->get('/test-teacher-route');
        $response->assertStatus(403);
        
        // Учитель должен иметь доступ
        $response = $this->actingAs($teacher)->get('/test-teacher-route');
        $response->assertStatus(200);
    }
} 