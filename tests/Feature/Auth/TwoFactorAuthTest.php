<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\LoginCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_shows_verification_page_after_successful_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login/verify');
        $this->assertNotNull(session('login_user_id'));
        $this->assertEquals($user->id, session('login_user_id'));
    }

    /** @test */
    public function verification_form_is_displayed()
    {
        $user = User::factory()->create();
        
        $this->withSession(['login_user_id' => $user->id])
            ->get('/login/verify')
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page->component('Auth/VerifyLoginCode'));
    }

    /** @test */
    public function verification_fails_with_invalid_code()
    {
        $user = User::factory()->create();
        
        // Generate a valid code
        $validCode = $user->generateLoginCode();
        
        // Attempt with invalid code
        $response = $this->withSession(['login_user_id' => $user->id])
            ->post('/login/verify', [
                'code' => '000000', // Wrong code
            ]);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors('code');
        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function verification_succeeds_with_valid_code()
    {
        $user = User::factory()->create();
        
        // Generate a valid code
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
        ]);
        
        // Attempt with valid code
        $response = $this->withSession(['login_user_id' => $user->id])
            ->post('/login/verify', [
                'code' => '123456',
            ]);
        
        $response->assertRedirect('/dashboard');
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    /** @test */
    public function expired_login_code_is_rejected()
    {
        $user = User::factory()->create();
        
        // Generate an expired code
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->subMinutes(10), // Expired 10 minutes ago
        ]);
        
        // Attempt with expired code
        $response = $this->withSession(['login_user_id' => $user->id])
            ->post('/login/verify', [
                'code' => '123456',
            ]);
        
        $response->assertRedirect();
        $response->assertSessionHasErrors('code');
        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function access_is_denied_without_login_user_id_in_session()
    {
        $response = $this->get('/login/verify');
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function access_is_denied_when_submitting_code_without_login_user_id_in_session()
    {
        $response = $this->post('/login/verify', [
            'code' => '123456',
        ]);
        
        $response->assertRedirect('/login');
    }

    /** @test */
    public function successful_verification_clears_all_login_codes()
    {
        $user = User::factory()->create();
        
        // Generate multiple codes
        LoginCode::factory()->count(3)->create([
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(10),
        ]);
        
        // Generate one valid code to use
        $validCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(10),
        ]);
        
        // Verify there are 4 codes before verification
        $this->assertEquals(4, $user->loginCodes()->count());
        
        // Attempt with valid code
        $response = $this->withSession(['login_user_id' => $user->id])
            ->post('/login/verify', [
                'code' => '123456',
            ]);
        
        // Verify all codes are deleted after successful verification
        $this->assertEquals(0, $user->loginCodes()->count());
    }
} 