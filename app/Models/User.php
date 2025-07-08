<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
        /**
     * Accessor appended attributes.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'class',
        'name',
        'class_letter',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'role',
        'school',
        'school_class_id',
        'class_letter',
        'region',
        'city',
        'district',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Get the class number attribute (legacy).
     *
     * @return int|null
     */
    public function getClassAttribute(): ?int
    {
        return $this->schoolClass?->class_number;
    }

    public function getClassLetterAttribute(): ?string
    {
        return $this->schoolClass?->class_letter;
    }

    public function scopeOrderByName($query)
    {
        $query->orderBy('last_name')->orderBy('first_name');
    }

    public function scopeWhereRole($query, $role)
    {
        if (in_array($role, ['student', 'parent', 'teacher', 'director'])) {
            $query->where('role', $role);
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('last_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public function isDemoUser()
    {
        return $this->email === 'johndoe@example.com';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where($field ?? 'id', $value)->withTrashed()->firstOrFail();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function user_votes(): HasMany
    {
        return $this->hasMany(UserVote::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'account_id');
    }

    public function petitions(): HasMany
    {
        return $this->hasMany(Petition::class);
    }

    public function petitionSignatures(): HasMany
    {
        return $this->hasMany(PetitionSignature::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(PetitionSignature::class);
    }

    public function votings(): HasMany
    {
        return $this->hasMany(Voting::class);
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    public function loginCodes(): HasMany
    {
        return $this->hasMany(LoginCode::class);
    }

    public function generateVerificationCode()
    {
        $code = rand(100000, 999999);
        $this->verificationCodes()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(30),
        ]);
        return $code;
    }

    public function verifyCode($code)
    {
        $verificationCode = $this->verificationCodes()
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->first();

        if ($verificationCode) {
            $this->email_verified_at = now();
            $this->save();
            $verificationCode->delete();
            return true;
        }
        return false;
    }

    public function generateLoginCode(): string
    {
        $code = random_int(100000, 999999);

        $this->loginCodes()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        return $code;
    }

    public function verifyLoginCode(string $code): bool
    {
        $loginCode = $this->loginCodes()
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($loginCode) {
            // Code is valid, we can delete all codes for this user now
            $this->loginCodes()->delete();
            return true;
        }

        return false;
    }
}
