<?php

namespace DevMcC\PackageDev\Config;

use DevMcC\PackageDev\Command\HelpCommand;
use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Command\SymlinkCreateCommand;
use DevMcC\PackageDev\Command\SymlinkRemoveCommand;
use DevMcC\PackageDev\Command\LinkCommand;
use DevMcC\PackageDev\Command\PharCommand;
use DevMcC\PackageDev\Command\UnlinkCommand;

class CommandMapping
{
    /**
     * @return string[]
     */
    public function getMapping(): array
    {
        return [
            HelpCommand::COMMAND_NAME          => HelpCommand::class,
            InitCommand::COMMAND_NAME          => InitCommand::class,
            LinkCommand::COMMAND_NAME          => LinkCommand::class,
            UnlinkCommand::COMMAND_NAME        => UnlinkCommand::class,
            SymlinkCreateCommand::COMMAND_NAME => SymlinkCreateCommand::class,
            SymlinkRemoveCommand::COMMAND_NAME => SymlinkRemoveCommand::class,
            PharCommand::COMMAND_NAME          => PharCommand::class,
        ];
    }

    public function commandExists(string $command): bool
    {
        return isset($this->getMapping()[$command]);
    }
}
