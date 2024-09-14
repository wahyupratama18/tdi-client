<?php

namespace App\Actions\Attendance;

use App\Models\Attendance;
use App\Models\Lecture;
use App\Models\Semester;
use Carbon\Carbon;

class Calculation
{
    protected static function totalStudents(): int
    {
        return Semester::query()
            ->active()
            ->with([
                'schedules' => fn ($query) => $query->withCount('students'),
                // ->whereHas('lectures', fn ($query) => $query->whereDate('date', now()->toDateString()))
            ])
            ->first()
            ->schedules
            ->sum('students_count');
    }

    public static function total(): object
    {
        $attend = Attendance::query()
            ->whereDate('created_at', now()->toDateString())
            ->count();

        return (object) [
            'attend' => $attend,
            'absent' => self::totalStudents() - $attend,
        ];
    }

    public static function timeLeft(Carbon $created): string
    {
        $lecture = cache()->remember('lecture', 3600, fn (): ?Lecture => Lecture::query()
            ->whereDate('date', now()->toDateString())
            ->first()
        );

        return match (now()->greaterThan($lecture->home_time)) {
            true => $lecture->home_time->timespan($created),
            false => ($lecture->attend_time->greaterThan($created) ? ' ' : '- ').
            $lecture->attend_time->timespan($created),
        };
    }
}
