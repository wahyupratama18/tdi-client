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
            ['order', 'date', 'schedule_id', 'created_at', 'updated_at'],
        );

        Lecture::query()
        ->whereNotIn('ulid', $data->pluck('ulid'))
        ->whereIn('schedule_id', $data->pluck('schedule_id'))
        ->delete();
    }
}
