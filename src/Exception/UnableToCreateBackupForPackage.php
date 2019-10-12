<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class UnableToCreateBackupForPackage extends Exception
{
    public const MESSAGE_FORMAT = 'An error occured while trying to create a backup package "%s"';

    public function __construct(string $package)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $package)
        );
    }
}
