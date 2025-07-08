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

    protected $fillable = ['content', 'user_id', 'commentable_id', 'commentable_type'];

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
}
