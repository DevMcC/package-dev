<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToCreateSymlinkForPackage extends Exception
{
    private const MESSAGE_FORMAT = 'An error occured while trying to create a symlink for package "%s"';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
