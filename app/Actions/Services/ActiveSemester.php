<?php

namespace App\Actions\Services;

use App\Models\Semester;

trait ActiveSemester
{
    protected function activeSemester(): ?Semester
    {
        return Semester::query()->active()->first();
    }
}
