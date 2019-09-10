<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class OperatingSystemNotSupported extends Exception
{
    private const DEFAULT_MESSAGE = 'Operating system is not supported';

    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
