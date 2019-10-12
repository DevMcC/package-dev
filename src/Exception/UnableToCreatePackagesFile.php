<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class UnableToCreatePackagesFile extends Exception
{
    public const MESSAGE = 'An error occured while trying to create the packages file';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
