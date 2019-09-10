<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class InvalidPackageName extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Package name "%s" is invalid', $message),
            $code,
            $previous
        );
    }
}
