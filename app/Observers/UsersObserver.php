<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;

class UsersObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        $userId = auth()->user()->id;
        $user->created_by = $userId;
        $user->updated_by = $userId;
    }

    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        $userId = auth()->user()->id;
        $user->updated_by = $userId;
    }

    public function updated(User $user)
    {
        //
    }

    public function saved(User $user)
    {
        $dataId = $user->id;
        $roles = request()->get('roles');
        if ($roles) {
            $key = "users_id";
            $this->removeRole($key, $dataId);
            for ($i = 0; $i < count($roles); $i++) {
                $model = app("App\Models\\" . Str::studly('role_users'));
                $data['users_id'] = $dataId;
                $data['roles_id'] = $roles[$i];
                $model::create($data);
            }
        }
    }

    public function removeRole($key, $dataId)
    {
        $model = app("App\Models\\" . Str::studly('role_users'));
        $model = $model::where($key, $dataId);
        if (!$model->count()) return false;
        $model->forceDelete();
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
