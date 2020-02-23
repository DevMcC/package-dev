<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\CommandArgument\CommandOptionalArgument;
use DevMcC\PackageDev\Config\CommandMapping;

class HelpCommand implements Command
{
    public const COMMAND_NAME = 'help';
    public const COMMAND_USAGE = 'package-dev help [command]';
    public const COMMAND_DESCRIPTION = 'Displays help for a command.';

    /** @var CommandOptionalArgument $commandOptionalArgument */
    private $commandOptionalArgument;
    /** @var CommandMapping $commandMapping */
    private $commandMapping;
    /** @var Output $output */
    private $output;

    public function __construct(
        CommandOptionalArgument $commandOptionalArgument,
        CommandMapping $commandMapping,
        Output $output
    ) {
        $this->commandOptionalArgument = $commandOptionalArgument;
        $this->commandMapping = $commandMapping;
        $this->output = $output;
    }

    public function handle(): void
    {
        if ($this->commandOptionalArgument->commandWasNotSupplied()) {
            $this->outputCommandUsage(self::class);

            return;
        }

        $commandClassName = $this->commandMapping->getMapping()[$this->commandOptionalArgument->command()];

        if ($commandClassName == self::class) {
            $this->output->line('<recursion>');
            $this->output->line();
        }

        $this->outputCommandUsage($commandClassName);

        if ($commandClassName == self::class) {
            $this->output->line();
            $this->output->line('</recursion>');
        }
    }

    private function outputCommandUsage(string $className): void
    {
        $this->output->line('Usage:');
        $this->output->line($className::COMMAND_USAGE, 1);
        $this->output->line();
        $this->output->line('Description:');
        $this->output->line($className::COMMAND_DESCRIPTION, 1);
    }
}
