<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToReadFromPackagesFile extends Exception
{
    /** @var string $message */
    protected $message = 'An error occured while trying to read from the packages file';
}
