<?php

namespace DevMcC\PackageDev\Core;

use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Core\Autoloading\DependencyInjection;
use DevMcC\PackageDev\Exception\Command\CommandNotFound;
use DevMcC\PackageDev\Exception\Command\CommandWasNotSupplied;
use DevMcC\PackageDev\Exception\Command\TerminateCommand;
use DevMcC\PackageDev\Command\Command;
use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use Exception;

class CommandHandler
{
    /** @var ProcessArguments $processArguments */
    private $processArguments;
    /** @var CommandMapping $commandMapping */
    private $commandMapping;
    /** @var DependencyInjection $dependencyInjection */
    private $dependencyInjection;
    /** @var Output $output */
    private $output;

    public function __construct(
        ProcessArguments $processArguments,
        CommandMapping $commandMapping,
        DependencyInjection $dependencyInjection,
        Output $output
    ) {
        $this->processArguments = $processArguments;
        $this->commandMapping = $commandMapping;
        $this->dependencyInjection = $dependencyInjection;
        $this->output = $output;
    }

    /**
     * @throws TerminateCommand
     */
    public function handle(): void
    {
        try {
            $this->handleCommand();
        } catch (CommandWasNotSupplied $e) {
            $this->outputUsage();
        } catch (Exception $e) {
            $this->outputError($e);

            throw new TerminateCommand;
        }
    }

    private function handleCommand(): void
    {
        $command = $this->getCommand();
        $command->handle();
    }

    /**
     * @throws CommandWasNotSupplied
     * @throws CommandNotFound
     */
    private function getCommand(): Command
    {
        if ($this->processArguments->commandWasNotSupplied()) {
            throw new CommandWasNotSupplied;
        }

        $command = $this->processArguments->command();

        if (!$this->commandMapping->commandExists($command)) {
            throw new CommandNotFound($command);
        }

        $commandClassName = $this->commandMapping->getMapping()[$command];

        return $this->dependencyInjection->resolveClassName($commandClassName);
    }

    private function outputUsage(): void
    {
        $this->output->line('PackageDev');
        $this->output->line();
        $this->output->line('Usage:');
        $this->output->line('package-dev [command] [arguments]', 1);
        $this->output->line();
        $this->output->line('Commands:');
        $this->output->list(array_map(
            function (string $command) {
                return [$command, $this->commandMapping->getMapping()[$command]::COMMAND_DESCRIPTION];
            },
            array_keys($this->commandMapping->getMapping())
        ));
    }

    private function outputError(Exception $exception): void
    {
        $this->output->line(sprintf('ERROR: %s', $exception->getMessage()));
    }
}
