<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class UnableToCreatePackagesDirectory extends Exception
{
    private const DEFAULT_MESSAGE = 'An error occured while trying to create the packages directory';

    public function __construct(string $message = self::DEFAULT_MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
