<?php

namespace App\Actions\Server;

use App\Enums\SyncList;
use Illuminate\Http\Client\Response;

class ConnectClassrooms extends Launcher
{
    protected SyncList $sync = SyncList::CLASSROOMS;

    public function launch(): Response
    {
        return $this->connect()->get(
            TDIConnection::path($this->replaceURL())
        );
    }
}
