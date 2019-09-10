<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class EnvironmentNotInitialized extends Exception
{
    private const DEFAULT_MESSAGE = 'Environment is not initialized';

    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
