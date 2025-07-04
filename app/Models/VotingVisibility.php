<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingVisibility extends Model
{
    protected $fillable = [
        'voting_id',
        'role',
        'class_number',
        'class_letter',
    ];
}
