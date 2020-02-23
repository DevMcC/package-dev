<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;

class SymlinkCreateCommand implements Command
{
    public const COMMAND_NAME = 'symlink-create';
    public const COMMAND_USAGE = 'package-dev symlink-create';
    public const COMMAND_DESCRIPTION = 'Creates a symlink for all linked packages.';

    /** @var Environment $environment */
    private $environment;
    /** @var Output $output */
    private $output;

    public function __construct(
        Environment $environment,
        Output $output
    ) {
        $this->environment = $environment;
        $this->output = $output;
    }

    public function handle(): void
    {
        $this->environment->createSymlinks();
        $this->output->line('All symlinks have been created');
    }
}
