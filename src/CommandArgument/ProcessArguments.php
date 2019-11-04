<?php

namespace DevMcC\PackageDev\CommandArgument;

class ProcessArguments
{
    /** @var string $command */
    private $command;
    /** @var string $argument */
    private $argument;

    public function __construct(array $arguments)
    {
        $this->command = $this->parseArgument($arguments[1] ?? null);
        $this->argument = $this->parseArgument($arguments[2] ?? null);
    }

    public function commandWasNotSupplied(): bool
    {
        return is_null($this->command);
    }

    public function argumentWasNotSupplied(): bool
    {
        return is_null($this->argument);
    }

    public function command(): ?string
    {
        return $this->command;
    }

    public function argument(): ?string
    {
        return $this->argument;
    }

    private function parseArgument(?string $argument): ?string
    {
        if (is_null($argument)) {
            return null;
        }

        $argument = trim($argument);

        if ($argument == '') {
            return null;
        }

        return $argument;
    }
}
