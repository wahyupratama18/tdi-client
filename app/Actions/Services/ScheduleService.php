<?php

namespace App\Actions\Services;

use App\Models\Classroom;
use App\Models\Lecture;
use App\Models\Schedule;
use App\Models\Semester;
use Illuminate\Support\Collection;

class ScheduleService
{
    public static function classrooms(?Semester $semester, bool $remarks = true): ?Collection
    {
        return $semester?->schedules()
            ->with('classrooms')
            ->get()
            ->map(
                fn (Schedule $schedule) => $schedule->classrooms
                    ->map(function (Classroom $classroom) use ($schedule, $remarks) {
                        if ($remarks) {
                            $classroom->remarks = $schedule->parity->name;
                        }

                        return $classroom;
                    })
            )->flatten();
    }

    public static function lectures(Semester $semester): Collection
    {
        return $semester->schedules()->with('lectures')
            ->get()
            ->map(
                fn (Schedule $schedule) => $schedule->lectures
                    ->map(function (Lecture $lecture) use ($schedule) {
                        $lecture->remarks = $schedule->parity->name;

                        return $lecture;
                    })
            )->flatten();
    }
}
