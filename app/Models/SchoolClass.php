<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_number',
        'class_letter',
    ];

    /**
     * Get the full name of the class.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return "{$this->class_number}-{$this->class_letter}";
    }
}
