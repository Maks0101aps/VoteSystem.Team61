<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'voting_id', 'choice'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'choice' => 'string',
    ];

    /**
     * Get the user that owns the vote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the voting that this vote belongs to.
     */
    public function voting(): BelongsTo
    {
        return $this->belongsTo(Voting::class);
    }
}
