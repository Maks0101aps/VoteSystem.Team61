<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\LoginCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function users_can_request_login_code()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/login/verify');
        $this->assertDatabaseHas('login_codes', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function login_with_valid_code_authenticates_user()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);

        session(['login_user_id' => $user->id]);

        $response = $this->post('/login/verify', [
            'code' => '123456',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function login_with_invalid_code_shows_error()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);

        session(['login_user_id' => $user->id]);

        $response = $this->post('/login/verify', [
            'code' => '654321', // Wrong code
        ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertGuest();
    }

    /** @test */
    public function login_with_expired_code_shows_error()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->expired()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);

        session(['login_user_id' => $user->id]);

        $response = $this->post('/login/verify', [
            'code' => '123456',
        ]);

        $response->assertSessionHasErrors(['code']);
        $this->assertGuest();
    }

    /** @test */
    public function users_can_logout()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticated();
        
        $response = $this->delete('/logout');
        
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
} 