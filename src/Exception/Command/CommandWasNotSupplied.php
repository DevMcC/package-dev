<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class CommandWasNotSupplied extends Exception
{
    protected $message = 'Command was not supplied';
}
