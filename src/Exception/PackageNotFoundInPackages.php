<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class PackageNotFoundInPackages extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Package "%s" was not found in the packages directory', $message),
            $code,
            $previous
        );
    }
}
