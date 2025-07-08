<?php

namespace Tests\Unit\Models;

use App\Models\EmailVerificationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $verificationCode = new EmailVerificationCode();
        $fillable = [
            'user_id',
            'code',
            'expires_at',
        ];

        $this->assertEquals($fillable, $verificationCode->getFillable());
    }

    /** @test */
    public function it_can_generate_code()
    {
        $code = EmailVerificationCode::generateCode();
        
        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    }

    /** @test */
    public function it_can_create_for_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        
        $verificationCode = EmailVerificationCode::createForEmail('test@example.com');
        
        $this->assertInstanceOf(EmailVerificationCode::class, $verificationCode);
        $this->assertEquals($user->id, $verificationCode->user_id);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $verificationCode->code);
        $this->assertGreaterThan(now(), $verificationCode->expires_at);
    }

    /** @test */
    public function it_can_verify_code()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        
        $verificationCode = EmailVerificationCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);
        
        $this->assertTrue(EmailVerificationCode::verify('test@example.com', '123456'));
    }

    /** @test */
    public function it_fails_verification_with_wrong_code()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        
        $verificationCode = EmailVerificationCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);
        
        $this->assertFalse(EmailVerificationCode::verify('test@example.com', '654321'));
    }

    /** @test */
    public function it_fails_verification_with_expired_code()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        
        $verificationCode = EmailVerificationCode::factory()->expired()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);
        
        $this->assertFalse(EmailVerificationCode::verify('test@example.com', '123456'));
    }

    /** @test */
    public function it_cleans_up_old_codes_for_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
        
        // Create some old codes
        EmailVerificationCode::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);
        
        // Create a new code which should delete the old ones
        $newCode = EmailVerificationCode::createForEmail('test@example.com');
        
        // Should only have one code now
        $this->assertEquals(1, EmailVerificationCode::where('user_id', $user->id)->count());
        $this->assertEquals($newCode->id, EmailVerificationCode::where('user_id', $user->id)->first()->id);
    }
} 