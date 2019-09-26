<?php

namespace Dirim\BeginningPackage\Observers;

use Log;

class QueryLoggingObserver
{
    public function created($model)
    {
        $log = "created {$model} to {$model->getTable()} table";
        
        Log::channel('create_query_log')->info($log);
    }

    public function updated($model)
    {
        $oldModel = json_encode($model->getOriginal());

        $log = "updated {$model->getTable()} table from {$model} to {$oldModel}";

        Log::channel('update_query_log')->info($log);
    }

    public function deleted($model)
    {
        $log = "deleted {$model} data from {$model->getTable()} table";

        Log::channel('delete_query_log')->info($log);
    }
}