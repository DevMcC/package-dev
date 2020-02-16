<?php

namespace DevMcC\PackageDev\Exception\Core;

use Exception;

class TerminateCommand extends Exception
{
    /** @var string $message */
    protected $message = 'Terminate command';
}
