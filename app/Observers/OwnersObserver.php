<?php

namespace App\Observers;

use App\Models\Resources;
use Carbon\Carbon;
use Exception;

class OwnersObserver
{
    /**
     * Handle the Resources "created" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function creating(Resources $resources)
    {
        try {
            $userId = auth()->user()->id;
            $resources->created_by = $userId;
            $resources->updated_by = $userId;
            $resources->status = true;

            $timeStamp = Carbon::now();
            $resources->created_time = $timeStamp;
            $resources->updated_time = $timeStamp;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function created(Resources $resources)
    {
        //
    }

    /**
     * Handle the Resources "updated" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function updating(Resources $resources)
    {
        try {
            $userId = auth()->user()->id;
            $resources->updated_by = $userId;

            $timeStamp = Carbon::now();
            $resources->updated_time = $timeStamp;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function updated(Resources $resources)
    {
        //
    }

    /**
     * Handle the Resources "deleted" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function deleted(Resources $resources)
    {
        try {
            $resources->setAttribute('status', false);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Handle the Resources "restored" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function restored(Resources $resources)
    {
        try {
            $resources->setAttribute('status', true);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Handle the Resources "force deleted" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function forceDeleted(Resources $resources)
    {
        //
    }
}
