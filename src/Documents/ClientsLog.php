<?php

namespace Dirim\BeginningPackage\Documents;

use Moloquent;

class ClientsLog extends Moloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'ClientsLog';

    protected $fillable = [
        'uniqueID', 'ip', 'email',
        'status', 'path', 'url',
        'method','enteredAt',
        'enteredAt', 'disconnectTime',
    ];

    public $timestamps = false;
}
