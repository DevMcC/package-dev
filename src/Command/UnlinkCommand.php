<?php

namespace DevMcC\PackageDev\Command;

class UnlinkCommand implements Command
{
    public const COMMAND_NAME = 'unlink';
    public const COMMAND_USAGE = 'package-dev unlink [package]';
    public const COMMAND_DESCRIPTION = 'Unlinks a package from the PackageDev environment.';

    public function handle(): void
    {
        //
    }
}
