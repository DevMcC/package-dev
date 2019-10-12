<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class PackageArgumentWasNotSupplied extends Exception
{
    public const MESSAGE = 'Package argument was not supplied';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
