<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Voting extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'ends_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visibilities(): HasMany
    {
        return $this->hasMany(VotingVisibility::class);
    }

    public function voteOptions(): HasMany
    {
        return $this->hasMany(VoteOption::class);
    }

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Определяет, активно ли голосование
     *
     * @return bool
     */
    public function isActive()
    {
        return now()->lt($this->ends_at);
    }

    /**
     * Проверяет, голосовал ли пользователь
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function hasUserVoted(User $user)
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }
    
    /**
     * Count the total number of votes for this voting
     *
     * @return int
     */
    public function countTotalVotes(): int
    {
        return $this->votes()->count();
    }
}
