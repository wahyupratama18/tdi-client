<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $casts = [
        'attend_time' => 'timestamp',
        'home_time' => 'timestamp',
    ];

    /**
     * Get the student that owns the Attendance
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
