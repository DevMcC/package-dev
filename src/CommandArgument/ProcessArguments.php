<?php

namespace DevMcC\PackageDev\CommandArgument;

class ProcessArguments
{
    /**
     * @var string $command
     * @var string $argument
     */
    private $command;
    private $argument;

    public function __construct(array $arguments)
    {
        $this->command = $arguments[1] ?? null;
        $this->argument = $arguments[2] ?? null;
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
}
