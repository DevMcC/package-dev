<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class PackageArgumentWasNotSupplied extends Exception
{
    protected $message = 'Package argument was not supplied';
}
