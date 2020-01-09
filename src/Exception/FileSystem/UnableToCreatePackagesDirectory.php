<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToCreatePackagesDirectory extends Exception
{
    protected $message = 'An error occured while trying to create the packages directory';
}
