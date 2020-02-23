<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class CommandNotFound extends Exception
{
    private const MESSAGE_FORMAT = 'Command "%s" not found';

    public function __construct(string $command)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $command)
        );
    }
}
