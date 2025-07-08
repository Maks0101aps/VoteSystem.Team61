<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LoginCode extends Model
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
     * Generate a random login code
     *
     * @return string
     */
    public static function generateCode(): string
    {
        return sprintf('%06d', rand(0, 999999));
    }
    
    /**
     * Create a login code for the given user
     *
     * @param User $user
     * @return self
     */
    public static function createForUser(User $user): self
    {
        // Clean up old codes for this user
        self::where('user_id', $user->id)->delete();
        
        // Create new login code
        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);
    }
    
    /**
     * Verify the code for the given user
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    public static function verify(User $user, string $code): bool
    {
        $loginCode = self::where('user_id', $user->id)
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
            
        return $loginCode !== null;
    }
}
