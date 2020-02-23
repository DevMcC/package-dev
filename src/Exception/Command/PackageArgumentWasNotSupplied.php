<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class PackageArgumentWasNotSupplied extends Exception
{
    /** @var string $message */
    protected $message = 'Package argument was not supplied';
}
