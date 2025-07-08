<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Comment;

class Petition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'signatures_required',
        'user_id',
        'duration',
        'school_class_id',
    ];

    protected $appends = [
        'ends_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function signatures()
    {
        return $this->hasMany(PetitionSignature::class);
    }

    public function getEndsAtAttribute()
    {
        return $this->created_at->addHours($this->duration);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Проверяет, подписал ли пользователь петицию
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function isSignedByUser(User $user)
    {
        return $this->signatures()->where('user_id', $user->id)->exists();
    }
} 