<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;

class SymlinkRemoveCommand implements Command
{
    public const COMMAND_NAME = 'symlink-remove';
    public const COMMAND_USAGE = 'package-dev symlink-remove';
    public const COMMAND_DESCRIPTION = 'Removes the symlink from all linked packages.';

    /**
     * @var Environment $environment
     * @var Output $output
     */
    private $environment;
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
        $this->environment->removeSymlinks();
        $this->output->line('All symlinks have been removed');
    }
}
