<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;

class InitCommand implements Command
{
    public const COMMAND_NAME = 'init';
    public const COMMAND_USAGE = 'package-dev init';
    public const COMMAND_DESCRIPTION = 'Initializes the PackageDev environment.';

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
        if (!$this->environment->initialize()) {
            $this->output->line('Environment was already initialized');

            return;
        }

        $this->output->line('Environment has been initialized');
    }
}
