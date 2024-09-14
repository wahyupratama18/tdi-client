<?php

namespace App\Http\Controllers;

use App\Actions\Attendance\Calculation;
use App\Actions\Services\ActiveSemester;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    use ActiveSemester;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('create', Attendance::class);

        return view('attendance.index', [
            'total' => Calculation::total(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attend = $request->student->attendances()->firstOrCreate([]);

        return response()->json([
            'status' => $attend->wasRecentlyCreated ? true : $attend->created_at->translatedFormat('l, j F Y H:i:s'),
            'student' => (object) [
                'nim' => $request->student->nim,
                'name' => $request->student->name,
                'classroom' => $request->student->classroom->name,
                'timeLeft' => Calculation::timeLeft($attend->created_at),
                'site' => $request->student->classroom->location->name,
            ],
            'total' => Calculation::total(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
