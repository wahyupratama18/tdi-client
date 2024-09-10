<?php

namespace App\Http\Controllers;

use App\Actions\Server\LaunchTDI;
use App\Actions\Services\ActiveSemester;
use App\Actions\Services\ScheduleService;
use App\Actions\Synchronization\ProcessSync;
use App\Enums\SyncList;
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

        $latestSyncs = Synchronization::query()
            ->whereIn('id', Synchronization::selectRaw('MAX(id) as id')->groupBy('sync')->pluck('id'))
            ->get();

        $activeSemester = $this->activeSemester();

        return view('sync.index', [
            'synchronizations' => collect(SyncList::cases())->map(fn (SyncList $sync) => (object) [
                'sync' => $sync->value,
                'name' => $sync->read(),
                'authorized' => $request->user()->can('create', [Synchronization::class, $sync]),
                'last' => $latestSyncs->firstWhere('sync', $sync->lowercase())?->created_at->translatedFormat('l, j F Y H:i:s') ?? 'N/A',
                'api' => $sync->apiLoops(),
                'loops' => match ($sync) {
                    SyncList::STUDENTS => ScheduleService::classrooms($activeSemester)?->pluck('ulid'),
                    SyncList::ATTENDANCES => $attendance ? range(1, ceil($attendance / 100)) : [],
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
        ])->thenReturn();

        $sync = $request->user()->synchronizations()
            ->create(['sync' => $request->sync]);

        return response()->json([
            'message' => __('Synchronization successful!'),
            'sync' => $sync->created_at->translatedFormat('l, j F Y H:i:s'),
        ]);
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
