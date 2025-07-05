<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voting extends Model
{
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

    public function options(): HasMany
    {
        return $this->hasMany(VoteOption::class);
    }

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
