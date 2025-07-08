<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VotingVisibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'voting_id',
        'role',
        'class_number',
        'class_letter',
    ];
}
