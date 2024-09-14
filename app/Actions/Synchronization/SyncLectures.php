<?php

namespace App\Actions\Synchronization;

use App\Models\Lecture;
use Illuminate\Support\Collection;

class SyncLectures extends Sync
{
    protected function store(Collection $data): void
    {
        Lecture::query()->upsert(
            $data->toArray(),
            ['ulid'],
            ['order', 'date', 'schedule_id', 'attend_opened_at', 'attend_time', 'home_time', 'home_closed_at', 'created_at', 'updated_at', 'location'],
        );

        Lecture::query()
            ->whereNotIn('ulid', $data->pluck('ulid'))
            ->whereIn('schedule_id', $data->pluck('schedule_id'))
            ->delete();
    }
}
