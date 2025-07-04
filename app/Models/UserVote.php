<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVote extends Model
{
    protected $fillable = ['user_id', 'voting_id', 'vote_option_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vote_option()
    {
        return $this->belongsTo(VoteOption::class);
    }
}
