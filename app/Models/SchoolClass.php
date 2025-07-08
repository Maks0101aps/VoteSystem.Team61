<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get the students in the class
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the petitions of the class
     *
     * @return HasMany
     */
    public function petitions(): HasMany
    {
        return $this->hasMany(Petition::class);
    }
    
    /**
     * Count students in the class
     * 
     * @return int
     */
    public function countStudents(): int
    {
        return $this->users()->where('role', 'student')->count();
    }
    
    /**
     * Find a class by its number and letter
     * 
     * @param int $number
     * @param string $letter
     * @return self|null
     */
    public static function findByNumberAndLetter(int $number, string $letter): ?self
    {
        return self::where('class_number', $number)
            ->where('class_letter', $letter)
            ->first();
    }
    
    /**
     * Scope a query to order classes by number and letter
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('class_number')->orderBy('class_letter');
    }
}
