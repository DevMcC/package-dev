<?php

namespace DevMcC\PackageDev\Exception\FileSystem;

use Exception;

class UnableToRestorePackage extends Exception
{
    private const MESSAGE_FORMAT = 'An error occured while trying to restore package "%s"';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
