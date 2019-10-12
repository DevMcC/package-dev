<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class InvalidPackageName extends Exception
{
    public const MESSAGE_FORMAT = 'Package name "%s" is invalid';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
