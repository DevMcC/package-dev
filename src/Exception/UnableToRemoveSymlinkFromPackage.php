<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class UnableToRemoveSymlinkFromPackage extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('An error occured while trying to remove a symlink from package "%s"', $message),
            $code,
            $previous
        );
    }
}
