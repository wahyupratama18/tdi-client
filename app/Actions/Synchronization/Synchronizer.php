<?php

namespace App\Actions\Synchronization;

use Closure;

interface Synchronizer
{
    public function handle(array $json, Closure $next);
}
