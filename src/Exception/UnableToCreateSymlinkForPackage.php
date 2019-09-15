<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class UnableToCreateSymlinkForPackage extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('An error occured while trying to create a symlink for package "%s"', $message),
            $code,
            $previous
        );
    }
}
