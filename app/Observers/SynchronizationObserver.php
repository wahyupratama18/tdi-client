<?php

namespace App\Observers;

use App\Models\Synchronization;

class SynchronizationObserver
{
    /**
     * Handle the Synchronization "created" event.
     */
    public function created(Synchronization $synchronization): void
    {
        //
    }

    /**
     * Handle the Synchronization "updated" event.
     */
    public function updated(Synchronization $synchronization): void
    {
        //
    }

    /**
     * Handle the Synchronization "deleted" event.
     */
    public function deleted(Synchronization $synchronization): void
    {
        //
    }

    /**
     * Handle the Synchronization "restored" event.
     */
    public function restored(Synchronization $synchronization): void
    {
        //
    }

    /**
     * Handle the Synchronization "force deleted" event.
     */
    public function forceDeleted(Synchronization $synchronization): void
    {
        //
    }
}
