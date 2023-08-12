<?php

namespace App\Actions\Server;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ConnectSemester extends Launcher
{
    protected string $sync = 'semester';

    public function launch(): Response
    {
        return $this->connect()->get(tdiRoute($this->replaceURL()));
    }
}
