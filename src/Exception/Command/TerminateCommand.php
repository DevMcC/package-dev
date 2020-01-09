<?php

namespace DevMcC\PackageDev\Exception\Command;

use Exception;

class TerminateCommand extends Exception
{
    protected $message = 'Terminate command';
}
