<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class UnableToWriteToPackagesFile extends Exception
{
    public const MESSAGE = 'An error occured while trying to write to the packages file';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
