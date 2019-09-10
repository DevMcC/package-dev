<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class CommandNotFound extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Command "%s" not found', $message),
            $code,
            $previous
        );
    }
}
