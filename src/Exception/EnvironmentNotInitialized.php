<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class EnvironmentNotInitialized extends Exception
{
    public const MESSAGE = 'Environment is not initialized';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
