<?php

namespace DevMcC\PackageDev\Command;

class JunctionDropCommand implements Command
{
    public const COMMAND_NAME = 'junction-drop';
    public const COMMAND_USAGE = 'package-dev junction-drop [package]';
    public const COMMAND_DESCRIPTION = 'Drops the junction of a package.';

    public function handle(): void
    {
        //
    }
}
