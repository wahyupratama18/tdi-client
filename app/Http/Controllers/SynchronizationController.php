<?php

namespace App\Http\Controllers;

use App\Actions\Server\LaunchTDI;
use App\Actions\Services\ActiveSemester;
use App\Actions\Services\ScheduleService;
use App\Actions\Synchronization\ProcessSync;
use App\Http\Requests\StoreSynchronizationRequest;
use App\Http\Requests\UpdateSynchronizationRequest;
use App\Models\Attendance;
use App\Models\Synchronization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\View\View;

class SynchronizationController extends Controller
{
    use ActiveSemester;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $attendance = Attendance::query()->count();

        return view('sync.index', [
            'synchronizations' => collect(Synchronization::SYNC)
                ->map(fn (string $name, string $key) => (object) [
                    'sync' => $key,
                    'name' => $name,
                    'authorized' => $request->user()->can('create', [Synchronization::class, $key]),
                    'last' => Synchronization::query()->where('sync', $key)->latest()->first()?->created_at->translatedFormat('l, j F Y H:i:s') ?? 'N/A',
                    'api' => in_array($key, Synchronization::API),
                    'loops' => match ($key) {
                        'students' => ScheduleService::classrooms($this->activeSemester())?->pluck('ulid'),
                        'attendances' => $attendance ? range(1, ceil($attendance / 100)) : [],
                        default => [],
                    },
                ])->values(),
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
    public function store(StoreSynchronizationRequest $request): JsonResponse
    {
        Pipeline::send($request)->through([
            LaunchTDI::class,
            ProcessSync::class,
        ])
            ->thenReturn();

        $sync = $request->user()->synchronizations()
            ->create([
                'sync' => $request->sync,
            ]);

        return response()->json([
            'message' => 'Synchronization successful!',
            'sync' => $sync->created_at->translatedFormat('l, j F Y H:i:s'),
        ]);
    }

    protected function replacement(string $sync, ?string $id = null): string
    {
        $route = Synchronization::ROUTES[$sync];

        if ($id) {
            $route = str_replace(':id', $id, $route);
        }

        return $route;
    }

    /**
     * Display the specified resource.
     */
    public function show(Synchronization $synchronization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Synchronization $synchronization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSynchronizationRequest $request, Synchronization $synchronization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Synchronization $synchronization)
    {
        //
    }
}
