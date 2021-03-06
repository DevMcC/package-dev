<?php

namespace DevMcC\PackageDev\CommandArgument;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Exception\Command\CommandNotFound;

class CommandOptionalArgument
{
    /** @var string|null $command */
    private $command = null;

    /**
     * @throws CommandNotFound
     */
    public function __construct(
        CommandMapping $commandMapping,
        ProcessArguments $processArguments
    ) {
        if ($processArguments->argumentWasNotSupplied()) {
            return;
        }

        /** @var string $command */
        $command = $processArguments->argument();

        if (!$commandMapping->commandExists($command)) {
            throw new CommandNotFound($command);
        }

        $this->command = $command;
    }

    public function commandWasNotSupplied(): bool
    {
        return is_null($this->command);
    }

    public function command(): ?string
    {
        return $this->command;
    }
}
