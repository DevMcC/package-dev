<?php

namespace DevMcC\PackageDev\Config;

use DevMcC\PackageDev\Command\HelpCommand;
use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Command\JunctionCreateCommand;
use DevMcC\PackageDev\Command\JunctionDropCommand;
use DevMcC\PackageDev\Command\LinkCommand;
use DevMcC\PackageDev\Command\PharCommand;
use DevMcC\PackageDev\Command\UnlinkCommand;

class CommandMapping
{
    public function getMapping(): array
    {
        return [
            HelpCommand::COMMAND_NAME           => HelpCommand::class,
            InitCommand::COMMAND_NAME           => InitCommand::class,
            LinkCommand::COMMAND_NAME           => LinkCommand::class,
            UnlinkCommand::COMMAND_NAME         => UnlinkCommand::class,
            JunctionCreateCommand::COMMAND_NAME => JunctionCreateCommand::class,
            JunctionDropCommand::COMMAND_NAME   => JunctionDropCommand::class,
            PharCommand::COMMAND_NAME           => PharCommand::class,
        ];
    }

    public function commandExists(string $command): bool
    {
        return isset($this->getMapping()[$command]);
    }
}
