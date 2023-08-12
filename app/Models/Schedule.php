<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'parity',
    ];

    public const PARITIES = [
        1 => 'Ganjil',
        2 => 'Genap',
    ];

    /**
     * Get the semester that owns the Schedule
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * The classrooms that belong to the Schedule
     */
    public function classrooms(): HasMany
    {
        return $this->HasMany(Classroom::class);
    }

    /**
     * Get all of the lectures for the Schedule
     */
    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }

    public function remarks(): Attribute
    {
        return Attribute::make(
            get: fn () => self::PARITIES[$this->parity],
        );
    }
}
