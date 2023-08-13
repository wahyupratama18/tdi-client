<?php

namespace App\Actions\Synchronization;

use App\Models\Semester;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SyncSemester extends Sync
{
    protected function store(Collection $data): void
    {
        Semester::query()->upsert(
            $data->map(fn (array $semester) => collect($semester)->forget('schedules'))->toArray(),
            ['id'],
            ['year', 'remarks', 'is_active', 'created_at', 'updated_at'],
        );

        Semester::query()->find($data->pluck('id'))
            ->each(fn (Semester $semester) => $semester->schedules()->upsert(
                Arr::get($data->firstWhere('id', $semester->id), 'schedules.data', []),
                ['id'],
                ['semester_id', 'parity', 'created_at', 'updated_at'],
            ));
    }
}
