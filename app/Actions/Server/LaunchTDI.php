<?php

namespace App\Actions\Server;

use App\Actions\Services\ActiveSemester;
use App\Http\Controllers\AuthController;
use App\Http\Requests\StoreSynchronizationRequest;
use Closure;

class LaunchTDI
{
    use ActiveSemester;

    public function handle(StoreSynchronizationRequest $request, Closure $next)
    {
        $http = (match ($request->sync) {
            'students' => new ConnectStudents($request->id),
            'attendances' => new ConnectAttendances,
            'classrooms' => new ConnectClassrooms($this->activeSemester()->id),
            'lectures' => new ConnectLectures($this->activeSemester()->id),
            'semester' => new ConnectSemester,
        })->setToken($request->user()->token)->launch();

        $json = $http->json();

        if ($http->successful() && ! $json) {
            return (new AuthController)->logout($request);
        }

        return $next((object) [
            'result' => $json ?? [],
            'request' => $request,
        ]);
    }
}
