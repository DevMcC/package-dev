<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class PackageNotFoundInVendor extends Exception
{
    private const MESSAGE_FORMAT = 'Package "%s" was not found in the vendor directory';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
