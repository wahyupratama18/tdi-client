<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'schedule_id',
    ];

    /**
     * Get the semester that owns the Classroom
     */
    /* public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    } */

    /**
     * Get all of the students for the Classroom
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * The schedules that belong to the Classroom
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
