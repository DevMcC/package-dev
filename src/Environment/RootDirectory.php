<?php

namespace DevMcC\PackageDev\Environment;

class RootDirectory
{
    /** @var string $rootDirectory */
    private $rootDirectory;

    public function __construct(string $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function rootDirectory(): string
    {
        return $this->rootDirectory;
    }
}
