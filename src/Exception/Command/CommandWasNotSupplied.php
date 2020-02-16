<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class CommandWasNotSupplied extends Exception
{
    /** @var string $message */
    protected $message = 'Command was not supplied';
}
