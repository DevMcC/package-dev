<?php

namespace DevMcC\PackageDev\Command;

use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\CommandArgument\PackageArgument;

class LinkCommand implements Command
{
    public const COMMAND_NAME = 'link';
    public const COMMAND_USAGE = 'package-dev link [package]';
    public const COMMAND_DESCRIPTION = 'Links a package to the PackageDev environment.';

    /**
     * @var PackageArgument $packageArgument
     * @var Output $output
     */
    private $packageArgument;
    private $output;

    public function __construct(
        PackageArgument $packageArgument,
        Output $output
    ) {
        $this->packageArgument = $packageArgument;
        $this->output = $output;
    }

    public function handle(): void
    {
        $this->output->line($this->packageArgument->package());
    }
}
