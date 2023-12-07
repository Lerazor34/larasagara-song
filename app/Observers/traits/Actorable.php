<?php

namespace App\Observers\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait Actorable
{
    /**
     * Boot the trait
     *
     * @return void
     */
    public static function bootActorable()
    {
        static::creating(function ($model) {
            $user = Auth::user();
            $timeStamp = Carbon::now();
            if ($user) {
                $model->setAttribute('created_by', $user->id);
                $model->setAttribute('updated_by', $user->id);
            }
            $model->setAttribute('status', true);
            $model->setAttribute('created_time', $timeStamp);
            $model->setAttribute('updated_time', $timeStamp);
        });

        static::updating(function ($model) {
            $user = Auth::user();
            $timeStamp = Carbon::now();
            if ($user) {
                $model->setAttribute('updated_by', $user->id);
            }
            $model->setAttribute('updated_time', $timeStamp);
        });
    }
}
