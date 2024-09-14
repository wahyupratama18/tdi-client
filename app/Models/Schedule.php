<?php

namespace App\Models;

use App\Enums\Parities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'parity',
    ];

    protected function casts(): array
    {
        return [
            'parity' => Parities::class,
        ];
    }

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

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Classroom::class);
    }

    /**
     * Get all of the lectures for the Schedule
     */
    public function lectures(): HasMany
    {
        return $this->hasMany(Lecture::class);
    }
}
