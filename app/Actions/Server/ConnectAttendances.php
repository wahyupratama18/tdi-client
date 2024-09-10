<?php

namespace App\Actions\Server;

use App\Enums\SyncList;
use App\Models\Attendance;
use Illuminate\Http\Client\Response;

class ConnectAttendances extends Launcher
{
    protected SyncList $sync = SyncList::ATTENDANCES;

    public function launch(): Response
    {
        return $this->connect()->post(
            TDIConnection::path($this->replaceURL()),
            [
                'attendances' => Attendance::query()
                    ->with('student')
                    ->limit(100)
                    ->get()
                    ->map(fn (Attendance $attendance) => [
                        'nim' => $attendance->student->nim,
                        'date' => $attendance->created_at->toDateTimeString(),
                    ]),
            ]
        );
    }
}
