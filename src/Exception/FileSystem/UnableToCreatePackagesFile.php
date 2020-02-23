<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToCreatePackagesFile extends Exception
{
    /** @var string $message */
    protected $message = 'An error occured while trying to create the packages file';
}
