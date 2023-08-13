<?php

namespace App\Actions\Server;

use Illuminate\Http\Client\Response;

class ConnectClassrooms extends Launcher
{
    protected string $sync = 'classrooms';

    public function launch(): Response
    {
        return $this->connect()->get(tdiRoute($this->replaceURL()));
    }
}
