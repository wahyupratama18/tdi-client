<?php

namespace App\Actions\Server;

use App\Models\Attendance;
use Illuminate\Http\Client\Response;

class ConnectAttendances extends Launcher
{
    protected string $sync = 'attendances';

    public function launch(): Response
    {
        return $this->connect()->post(
            tdiRoute($this->replaceURL()),
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
