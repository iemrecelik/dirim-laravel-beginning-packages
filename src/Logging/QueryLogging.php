<?php

namespace Dirim\BeginningPackage\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class QueryLogging
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $logger = new Logger($config['driver']);
        
        $level = strtoupper($config['level']);

        $logger->pushHandler(new StreamHandler($config['path'], $level));

        return $logger;
    }
}