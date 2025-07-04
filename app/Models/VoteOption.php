<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    protected $fillable = ['voting_id', 'title'];

    public function voting()
    {
        return $this->belongsTo(Voting::class);
    }

    public function user_votes()
    {
        return $this->hasMany(UserVote::class);
    }
}
