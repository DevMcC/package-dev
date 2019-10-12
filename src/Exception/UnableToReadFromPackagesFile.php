<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class UnableToReadFromPackagesFile extends Exception
{
    public const MESSAGE = 'An error occured while trying to read from the packages file';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
