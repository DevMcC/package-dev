<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class UnableToCreatePackagesDirectory extends Exception
{
    public const MESSAGE = 'An error occured while trying to create the packages directory';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
