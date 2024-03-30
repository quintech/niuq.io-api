<?php


namespace App\Services\Log;


use Illuminate\Database\Eloquent\Model;

trait ModelLogActivity
{
    /**
     * Generate log for creating data
     * @param  Model  $model
     */
    protected function createdLogger(Model $model)
    {
        ActivityLogger::activity('info', 'Table name:'.$model->getTable().' created:'.$model);
    }

    /**
     * Generate log for updating data
     * @param  Model  $model
     */
    protected function updatedLogger(Model $model)
    {
        $changes = [];
        foreach ($model->getDirty() as $key => $value) {
            $original      = $model->getOriginal($key);
            $changes[$key] = [
                'old' => $original,
                'new' => $value
            ];
        }
        ActivityLogger::activity('info', 'Table name:'.$model->getTable().' primary key:'.$model->getKey().', edited:'.json_encode($changes));
    }

    /**
     * Generate log for deleting data
     * @param  Model  $model
     */
    protected function deletedLogger(Model $model)
    {
        if (method_exists(get_class($model), 'trashed')) {
            ActivityLogger::activity('info', 'Table name:'.$model->getTable().' soft deleted:'.$model);
        } else {
            ActivityLogger::activity('info', 'Table name:'.$model->getTable().' deleted:'.$model);
        }
    }

    /**
     * Generate log for restoring data
     * @param  Model  $model
     */
    protected function restoredLogger(Model $model)
    {
        ActivityLogger::activity('info', 'Table name:'.$model->getTable().' restored:'.$model);
    }

    /**
     * Generate log for force deleting data
     * @param  Model  $model
     */
    protected function forceDeletedLogger(Model $model)
    {
        ActivityLogger::activity('info', 'Table name:'.$model->getTable().' force deleted:'.$model);
    }
}
