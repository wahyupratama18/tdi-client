<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'order',
        'schedule_id',
        'date',
        'attend_time',
        'home_time',
    ];

    protected $casts = [
        'date' => 'date',
        'attend_time' => 'datetime',
        'home_time' => 'datetime',
    ];

    /**
     * Get the schedule that owns the Lecture
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * The students that belong to the Lecture
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    protected function attendanceStatus(int $status): BelongsToMany
    {
        return $this->students()->wherePivot('status', $status);
    }

    protected function attendances(int $status, int $remarks): BelongsToMany
    {
        return $this->attendanceStatus($status)->wherePivot('remarks', $remarks);
    }

    protected function arrayRemarksAttendance(int $status, array $remarks): BelongsToMany
    {
        return $this->attendanceStatus($status)->wherePivotIn('remarks', $remarks);
    }

    public function attendedOnTime(): BelongsToMany
    {
        return $this->attendances(1, 1);
    }

    public function attendedLate(): BelongsToMany
    {
        return $this->attendances(1, 2);
    }

    public function backHome(): BelongsToMany
    {
        return $this->arrayRemarksAttendance(2, [1, 3]);
    }

    public function backHomeLate(): BelongsToMany
    {
        return $this->attendances(2, 2);
    }

    public function transDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date->translatedFormat('l, j F Y')
        );
    }
}
