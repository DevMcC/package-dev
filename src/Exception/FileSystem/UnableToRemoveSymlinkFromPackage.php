<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToRemoveSymlinkFromPackage extends Exception
{
    private const MESSAGE_FORMAT = 'An error occured while trying to remove a symlink from package "%s"';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
