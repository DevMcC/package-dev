<?php

namespace DevMcC\PackageDev\Exception\Environment;

use Exception;

class PharArchiveAlreadyExists extends Exception
{
    private const MESSAGE_FORMAT = '%s already exists, ignoring';

    public function __construct(string $fname)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $fname)
        );
    }
}
