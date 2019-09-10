<?php

namespace DevMcC\PackageDev\Command;

class JunctionCreateCommand implements Command
{
    public const COMMAND_NAME = 'junction-create';
    public const COMMAND_USAGE = 'package-dev junction-create [package]';
    public const COMMAND_DESCRIPTION = 'Creates a junction for a package.';

    public function handle(): void
    {
        //
    }
}
