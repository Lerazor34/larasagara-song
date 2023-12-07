<?php

namespace App\Observers;

use App\Models\Resources;
use Illuminate\Support\Str;

class TrancientObserver
{
    /**
     * Handle the Resources "created" event.
     *
     * @param  \App\Models\Resources  $resources
     * @return void
     */
    public function saved(Resources $resources)
    {
        $dataId = $resources->id;
        $dataTrancient = request()->get('trancient');
        if ($dataTrancient) {
            foreach ($dataTrancient as $modelName => $data) {
                $key = $resources->getTableName() . "_id";
                $this->removeData($key, $modelName, $dataId);
                foreach ($data as $items) {
                    $model = app("App\Models\\" . Str::studly($modelName));
                    $items[$key] = $dataId;
                    $model::create($items);
                }
            }
        }
    }

    public function removeData($key, $modelName, $dataId)
    {
        $model = app("App\Models\\" . Str::studly($modelName));
        $model = $model::where($key, $dataId);
        if (!$model->count()) return false;
        $model->forceDelete();
    }
}
