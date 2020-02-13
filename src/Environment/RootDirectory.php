<?php

namespace DevMcC\PackageDev\Environment;

class RootDirectory
{
    /** @var string $rootDirectory */
    private $rootDirectory;
    /** @var string $packageDevSrcDirectory */
    private $packageDevSrcDirectory;

    public function __construct(string $rootDirectory, string $packageDevSrcDirectory)
    {
        $this->rootDirectory = $rootDirectory;
        $this->packageDevSrcDirectory = $packageDevSrcDirectory;
    }

    public function rootDirectory(): string
    {
        return $this->rootDirectory;
    }

    public function packageDevSrcDirectory(): string
    {
        return $this->packageDevSrcDirectory;
    }
}
