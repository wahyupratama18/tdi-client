<?php

namespace App\Policies;

use App\Models\Semester;
use App\Models\Synchronization;
use App\Models\User;

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
    public function create(User $user, string $sync): bool
    {
        return $sync == 'semester' ? true : $this->authorizeActiveSync($sync);
    }

    protected function authorizeActiveSync(string $sync): bool
    {
        return Semester::query()->active()
            ->when(in_array($sync, Synchronization::API), fn ($query) => $query->has('schedules.classrooms'))
            ->exists();
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
