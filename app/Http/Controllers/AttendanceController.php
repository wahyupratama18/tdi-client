<?php

namespace App\Http\Controllers;

use App\Actions\Services\ActiveSemester;
use App\Models\Attendance;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    use ActiveSemester;

    protected function students(): int
    {
        return $this->activeSemester()->load(
            ['schedules.classrooms' => fn ($query) => $query->withCount('students')]
        )->schedules->pluck('classrooms')->flatten()->sum('students_count');
    }

    protected function total(): object
    {
        $attend = Attendance::query()->whereDate('created_at', now()->toDateString())->count();
        return (object) [
            'attend' => $attend,
            'absent' => $this->students() - $attend,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('create', Attendance::class);

        return view('attendance.index', [
            'total' => $this->total(),
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
        $student = Student::query()->where('nim', $request->nim)->with('classroom')->first();
        
        $attend = $student->attendances()->firstOrCreate([]);

        return response()->json([
            'status' => $attend->wasRecentlyCreated ? true : $attend->created_at->translatedFormat('l, j F Y H:i:s'),
            'student' => (object) [
                'nim' => $student->nim,
                'name' => $student->name,
                'classroom' => $student->classroom->name,
                'timeLeft' => $this->timeLeft($attend->created_at),
            ],
            'total' => $this->total(),
        ]);
    }

    protected function timeLeft(Carbon $created): string
    {
        $compare = now()->greaterThan(env('MIN_HOME'));

        if ($compare) {
            return Carbon::parse(env('MIN_HOME'))->timespan($created);
        }

        $attend = Carbon::parse(env('MAX_ATTENDANCE'));

        return ($attend->greaterThan($created) ? ' ' : '- ').$attend->timespan($created);
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
