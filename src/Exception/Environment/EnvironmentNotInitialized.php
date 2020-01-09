<?php

namespace DevMcC\PackageDev\Exception\Environment;

use Exception;

class EnvironmentNotInitialized extends Exception
{
    protected $message = 'Environment is not initialized';
}
