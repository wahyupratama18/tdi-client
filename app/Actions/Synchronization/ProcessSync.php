<?php

namespace App\Actions\Synchronization;

use App\Enums\SyncList;
use Closure;
use Illuminate\Support\Facades\Pipeline;

class ProcessSync
{
    public function handle(object $data, Closure $next)
    {
        Pipeline::send($data->result)
            ->through(match ($data->sync) {
                SyncList::STUDENTS => SyncStudents::class,
                SyncList::ATTENDANCES => SyncAttendances::class,
                SyncList::CLASSROOMS => SyncClassrooms::class,
                SyncList::LECTURES => SyncLectures::class,
                SyncList::SEMESTER => SyncSemester::class,
            })->thenReturn();
    }
}
