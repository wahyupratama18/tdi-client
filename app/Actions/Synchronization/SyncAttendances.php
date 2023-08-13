<?php

namespace App\Actions\Synchronization;

use App\Models\Attendance;
use Illuminate\Support\Collection;

class SyncAttendances extends Sync
{
    protected function store(Collection $data): void
    {
        Attendance::query()->limit(100)->delete();
    }
}
