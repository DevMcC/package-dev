<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToWriteToPackagesFile extends Exception
{
    /** @var string $message */
    protected $message = 'An error occured while trying to write to the packages file';
}
