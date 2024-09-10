<?php

namespace App\Actions\Server;

use App\Enums\SyncList;
use Illuminate\Http\Client\Response;

class ConnectStudents extends Launcher
{
    protected SyncList $sync = SyncList::STUDENTS;

    public function launch(): Response
    {
        return $this->connect()->get(
            TDIConnection::path($this->replaceURL())
        );
    }
}
