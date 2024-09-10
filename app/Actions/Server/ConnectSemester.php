<?php

namespace App\Actions\Server;

use App\Enums\SyncList;
use Illuminate\Http\Client\Response;

class ConnectSemester extends Launcher
{
    protected SyncList $sync = SyncList::SEMESTER;

    public function launch(): Response
    {
        return $this->connect()->get(
            TDIConnection::path($this->replaceURL())
        );
    }
}
