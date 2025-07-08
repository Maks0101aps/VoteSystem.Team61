<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'commentable_id', 
        'commentable_type', 
        'content'
    ];

    protected $appends = [
        'created_at_formatted',
        'created_at_human',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
    
    /**
     * Format the created_at date in a readable format
     * 
     * @return string
     */
    public function formatCreatedAt(): string
    {
        return Carbon::parse($this->created_at)->format('j M Y, g:i a');
    }
    
    /**
     * Get the formatted created_at attribute
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    /**
     * Get the human-readable created_at attribute
     */
    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
    
    /**
     * Scope a query to only include comments of a given type
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('commentable_type', $type);
    }

    /**
     * Scope a query to only include comments for petitions
     */
    public function scopeForPetitions($query)
    {
        return $query->ofType(Petition::class);
    }

    /**
     * Scope a query to only include comments for votings
     */
    public function scopeForVotings($query)
    {
        return $query->ofType(Voting::class);
    }
}
