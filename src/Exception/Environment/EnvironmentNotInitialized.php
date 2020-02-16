<?php

namespace DevMcC\PackageDev\Exception\Environment;

use Exception;

class EnvironmentNotInitialized extends Exception
{
    /** @var string $message */
    protected $message = 'Environment is not initialized';
}
