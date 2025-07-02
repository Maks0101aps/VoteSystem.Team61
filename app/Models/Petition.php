<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'signatures_required',
        'user_id',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signatures()
    {
        return $this->hasMany(PetitionSignature::class);
    }

    public function getEndsAtAttribute()
    {
        return $this->created_at->addHours($this->duration);
    }
} 