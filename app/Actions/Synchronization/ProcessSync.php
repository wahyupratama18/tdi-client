<?php

namespace App\Actions\Synchronization;

use Closure;
use Illuminate\Support\Facades\Pipeline;

class ProcessSync
{
    public function handle(object $data, Closure $next)
    {
        Pipeline::send($data->result)
            ->through(match ($data->request->sync) {
                'students' => SyncStudents::class,
                'attendances' => SyncAttendances::class,
                'classrooms' => SyncClassrooms::class,
                'lectures' => SyncLectures::class,
                'semester' => SyncSemester::class,
            })
            ->thenReturn();
    }
}
