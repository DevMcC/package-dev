<?php

namespace DevMcC\PackageDev\Environment;

class RootDirectory
{
    /** @var string $rootDirectory */
    private $rootDirectory;
    /** @var string $packageDevRootDirectory */
    private $packageDevRootDirectory;

    public function __construct(string $rootDirectory, string $packageDevRootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
        $this->packageDevRootDirectory = $packageDevRootDirectory;
    }

    public function rootDirectory(): string
    {
        return $this->rootDirectory;
    }

    public function packageDevRootDirectory(): string
    {
        return $this->packageDevRootDirectory;
    }
}
