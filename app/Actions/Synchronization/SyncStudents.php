<?php

namespace App\Actions\Synchronization;

use App\Models\Student;
use Illuminate\Support\Collection;

class SyncStudents extends Sync
{
    protected function store(Collection $data): void
    {
        Student::query()->upsert(
            $data->toArray(),
            ['nim', 'qr'],
            ['classroom_id', 'name', 'created_at', 'updated_at'],
        );

        Student::query()
            ->whereNotIn('nim', $data->pluck('nim'))
            ->where('classroom_id', $data->first()['classroom_id'])
            ->delete();
    }
}
