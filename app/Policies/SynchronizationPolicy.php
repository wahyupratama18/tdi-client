<?php

namespace App\Policies;

use App\Enums\SyncList;
use App\Models\Semester;
use App\Models\Synchronization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SynchronizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Synchronization $synchronization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?SyncList $sync): bool
    {
        return $sync && $sync == SyncList::SEMESTER ? true : $this->authorizeActiveSync($sync);
    }

    protected function authorizeActiveSync(SyncList $sync): bool
    {
        return cache()->remember(
            'active_sync_'.$sync->name,
            60,
            fn () => Semester::query()->active()
                ->when($sync->apiLoops(), fn (Builder $query) => $query->has('schedules.classrooms'))
                ->exists()
        );
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Synchronization $synchronization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Synchronization $synchronization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Synchronization $synchronization): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Synchronization $synchronization): bool
    {
        return true;
    }
}
