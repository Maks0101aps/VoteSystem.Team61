<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoteOption extends Model
{
    use HasFactory;

    protected $fillable = ['voting_id', 'title'];

    public function voting(): BelongsTo
    {
        return $this->belongsTo(Voting::class);
    }

    public function user_votes(): HasMany
    {
        return $this->hasMany(UserVote::class);
    }
    
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'choice', 'title');
    }
    
    /**
     * Count the number of votes for this option
     *
     * @return int
     */
    public function countVotes(): int
    {
        return $this->votes()->count();
    }
    
    /**
     * Calculate the percentage of votes for this option
     *
     * @return float
     */
    public function calculatePercentage(): float
    {
        $totalVotes = $this->voting->countTotalVotes();
        
        if ($totalVotes == 0) {
            return 0;
        }
        
        return round(($this->countVotes() / $totalVotes) * 100, 1);
    }
}
