<?php

namespace DevMcC\PackageDev\Exception;

use Exception;

class CommandNotFound extends Exception
{
    public const MESSAGE_FORMAT = 'Command "%s" not found';

    public function __construct(string $command)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $command)
        );
    }
}
