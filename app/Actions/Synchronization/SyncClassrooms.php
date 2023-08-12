<?php

namespace App\Actions\Synchronization;

use App\Models\Classroom;
use Illuminate\Support\Collection;

class SyncClassrooms extends Sync
{
    protected function store(Collection $data): void
    {
        Classroom::query()->upsert(
            $data->toArray(),
            ['ulid'],
            ['name', 'schedule_id', 'created_at', 'updated_at'],
        );

        Classroom::query()
        ->whereNotIn('ulid', $data->pluck('ulid'))
        ->whereIn('schedule_id', $data->pluck('schedule_id'))
        ->delete();
    }
}
