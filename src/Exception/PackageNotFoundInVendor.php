<?php

namespace DevMcC\PackageDev\Exception;

use Exception;
use Throwable;

class PackageNotFoundInVendor extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Package "%s" was not found in the vendor directory', $message),
            $code,
            $previous
        );
    }
}
