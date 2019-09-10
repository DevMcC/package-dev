<?php

namespace DevMcC\PackageDev\Command;

class PharCommand implements Command
{
    public const COMMAND_NAME = 'phar';
    public const COMMAND_USAGE = 'package-dev phar';
    public const COMMAND_DESCRIPTION = 'Generates a .phar file for PackageDev.';

    public function handle(): void
    {
        //
    }
}
