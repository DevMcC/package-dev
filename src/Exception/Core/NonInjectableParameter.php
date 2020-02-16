<?php

namespace DevMcC\PackageDev\Exception\Core;

use Exception;

class NonInjectableParameter extends Exception
{
    private const MESSAGE_FORMAT = 'Parameter "%s" of %s::__construct is not injectable';

    public function __construct(string $className, string $parameter)
    {
        parent::__construct(
            sprintf(self::MESSAGE_FORMAT, $parameter, $className)
        );
    }
}
