<?php

use Dirim\BeginningPackage\Logging\QueryLogging;

$currentCreatedLogName = 'logs/query/created-'.date("d-m-Y").'.log';
$currentUpdatedLogName = 'logs/query/updated-'.date("d-m-Y").'.log';
$currentDeletedLogName = 'logs/query/deleted-'.date("d-m-Y").'.log';

return [
    'create_query_log' => [
        'driver' => 'custom',
        'via' => QueryLogging::class,
        'path' => storage_path($currentCreatedLogName.'created.log'),
        'level' => 'debug',
    ],
    
    'update_query_log' => [
        'driver' => 'custom',
        'via' => QueryLogging::class,
        'path' => storage_path($currentUpdatedLogName.'updated.log'),
        'level' => 'debug',
    ],

    'delete_query_log' => [
        'driver' => 'custom',
        'via' => QueryLogging::class,
        'path' => storage_path($currentDeletedLogName.'deleted.log'),
        'level' => 'debug',
    ],
];