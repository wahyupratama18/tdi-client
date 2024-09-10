<?php

namespace App\Actions\Server;

use App\Actions\Services\ActiveSemester;
use App\Enums\SyncList;
use App\Http\Controllers\AuthController;
use App\Http\Requests\StoreSynchronizationRequest;
use Closure;

class LaunchTDI
{
    use ActiveSemester;

    public function handle(StoreSynchronizationRequest $request, Closure $next)
    {
        $sync = SyncList::tryFrom($request->sync);

        $http = (match ($sync) {
            SyncList::STUDENTS => new ConnectStudents($request->id),
            SyncList::ATTENDANCES => new ConnectAttendances,
            SyncList::CLASSROOMS => new ConnectClassrooms($this->activeSemester()->id),
            SyncList::LECTURES => new ConnectLectures($this->activeSemester()->id),
            SyncList::SEMESTER => new ConnectSemester,
        })->setToken($request->user()->token)->launch();

        $json = $http->json();

        if ($http->successful() && ! $json) {
            return (new AuthController)->logout($request);
        }

        return $next((object) [
            'result' => $json ?? [],
            'request' => $request,
            'sync' => $sync,
        ]);
    }
}
