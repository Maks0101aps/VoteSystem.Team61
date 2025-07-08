<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerificationCode extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Generate a random verification code
     *
     * @return string
     */
    public static function generateCode(): string
    {
        return sprintf('%06d', rand(0, 999999));
    }
    
    /**
     * Create a verification code for the given email
     *
     * @param string $email
     * @return self
     */
    public static function createForEmail(string $email): self
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            throw new \Exception('User not found with this email.');
        }
        
        // Clean up old codes for this user
        self::where('user_id', $user->id)->delete();
        
        // Create new verification code
        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);
    }
    
    /**
     * Verify the code for the given email
     *
     * @param string $email
     * @param string $code
     * @return bool
     */
    public static function verify(string $email, string $code): bool
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return false;
        }
        
        $verificationCode = self::where('user_id', $user->id)
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
            
        return $verificationCode !== null;
    }
}
