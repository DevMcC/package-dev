<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\CommandArgument\PackageArgument;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;

class UnlinkCommand implements Command
{
    public const COMMAND_NAME = 'unlink';
    public const COMMAND_USAGE = 'package-dev unlink [package]';
    public const COMMAND_DESCRIPTION = 'Unlinks a package from the PackageDev environment.';

    /**
     * @var PackageArgument $packageArgument
     * @var Environment $environment
     * @var Output $output
     */
    private $packageArgument;
    private $environment;
    private $output;

    public function __construct(
        PackageArgument $packageArgument,
        Environment $environment,
        Output $output
    ) {
        $this->packageArgument = $packageArgument;
        $this->environment = $environment;
        $this->output = $output;
    }

    public function handle(): void
    {
        $package = $this->packageArgument->package();

        $this->environment->unlink($package);
        $this->output->line(sprintf('Package "%s" has been unlinked', $package));
    }
}
