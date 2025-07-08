<?php

namespace Tests\Unit\Models;

use App\Models\LoginCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $loginCode = new LoginCode();
        $fillable = [
            'user_id',
            'code',
            'expires_at',
        ];

        $this->assertEquals($fillable, $loginCode->getFillable());
    }

    /** @test */
    public function it_belongs_to_user()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $loginCode->user->id);
    }

    /** @test */
    public function it_can_generate_code()
    {
        $code = LoginCode::generateCode();
        
        $this->assertIsString($code);
        $this->assertEquals(6, strlen($code));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
    }

    /** @test */
    public function it_can_create_for_user()
    {
        $user = User::factory()->create();
        
        $loginCode = LoginCode::createForUser($user);
        
        $this->assertInstanceOf(LoginCode::class, $loginCode);
        $this->assertEquals($user->id, $loginCode->user_id);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $loginCode->code);
        $this->assertGreaterThan(now(), $loginCode->expires_at);
    }

    /** @test */
    public function it_can_verify_code()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);
        
        $this->assertTrue(LoginCode::verify($user, '123456'));
    }

    /** @test */
    public function it_fails_verification_with_wrong_code()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
            'expires_at' => now()->addMinutes(15),
        ]);
        
        $this->assertFalse(LoginCode::verify($user, '654321'));
    }

    /** @test */
    public function it_fails_verification_with_expired_code()
    {
        $user = User::factory()->create();
        $loginCode = LoginCode::factory()->expired()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);
        
        $this->assertFalse(LoginCode::verify($user, '123456'));
    }

    /** @test */
    public function it_cleans_up_old_codes_for_user()
    {
        $user = User::factory()->create();
        
        // Create some old codes
        LoginCode::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);
        
        // Create a new code which should delete the old ones
        $newCode = LoginCode::createForUser($user);
        
        // Should only have one code now
        $this->assertEquals(1, LoginCode::where('user_id', $user->id)->count());
        $this->assertEquals($newCode->id, LoginCode::where('user_id', $user->id)->first()->id);
    }
} 