<?php

namespace App\Actions\Server;

use Illuminate\Http\Client\Response;

class ConnectStudents extends Launcher
{
    protected string $sync = 'students';

    public function launch(): Response
    {
        return $this->connect()->get(
            TDIConnection::path($this->replaceURL())
        );
    }
}
