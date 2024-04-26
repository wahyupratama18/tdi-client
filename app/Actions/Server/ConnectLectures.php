<?php

namespace App\Actions\Server;

use Illuminate\Http\Client\Response;

class ConnectLectures extends Launcher
{
    protected string $sync = 'lectures';

    public function launch(): Response
    {
        return $this->connect()->get(TDIConnection::path($this->replaceURL()));
    }
}
