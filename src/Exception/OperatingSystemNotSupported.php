<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class OperatingSystemNotSupported extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Operating system "%s" is not supported', $message),
            $code,
            $previous
        );
    }
}