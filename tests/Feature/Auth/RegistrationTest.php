<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Models\SchoolClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /** @test */
    public function new_users_can_register()
    {
        $schoolClass = SchoolClass::factory()->create();
        
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role' => 'student',
            'school' => 'Test School',
            'class_number' => $schoolClass->class_number,
            'class_letter' => $schoolClass->class_letter,
            'region' => 'Test Region',
            'city' => 'Test City',
            'district' => 'Test District',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/verify-email');
        
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);
    }
    
    /** @test */
    public function email_verification_screen_can_be_rendered()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        
        $response = $this->actingAs($user)->get('/verify-email');
        
        $response->assertStatus(200);
    }
    
    /** @test */
    public function users_can_verify_email_with_valid_code()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        
        $verificationCode = EmailVerificationCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);
        
        $response = $this->actingAs($user)->post('/verify-email', [
            'code' => '123456',
        ]);
        
        $response->assertRedirect('/');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
    
    /** @test */
    public function verification_fails_with_invalid_code()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        
        $verificationCode = EmailVerificationCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);
        
        $response = $this->actingAs($user)->post('/verify-email', [
            'code' => '654321', // Wrong code
        ]);
        
        $response->assertSessionHasErrors(['code']);
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
    
    /** @test */
    public function users_can_request_new_verification_code()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);
        
        $this->actingAs($user);
        
        // Ensure we start with clean state
        EmailVerificationCode::query()->where('user_id', $user->id)->delete();
        
        $response = $this->get('/verify-email/resend');
        
        $response->assertRedirect();
        $this->assertDatabaseHas('email_verification_codes', [
            'user_id' => $user->id,
        ]);
    }
} 