<?php

namespace App\Actions\Synchronization;

use Closure;
use Illuminate\Support\Collection;

abstract class Sync
{
    public function handle(array $json = [], Closure $next)
    {
        $this->store(collect($json['data'] ?? []));

        return $next($json);
    }

    protected function store(Collection $data): void
    {
        # code...
    }
}