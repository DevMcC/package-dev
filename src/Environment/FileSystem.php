<?php

namespace DevMcC\PackageDev\Environment;

class FileSystem
{
    /**
     * @var OperatingSystem $operatingSystem
     * @var RootDirectory $rootDirectory
     */
    private $operatingSystem;
    private $rootDirectory;

    public function __construct(
        OperatingSystem $operatingSystem,
        RootDirectory $rootDirectory
    ) {
        $this->operatingSystem = $operatingSystem;
        $this->rootDirectory = $rootDirectory;
    }

    public function doesFileExist(string $file): bool
    {
        return is_file($this->path($file));
    }

    public function doesDirectoryExist(string $directory): bool
    {
        return is_dir($this->path($directory));
    }

    public function createFile(string $file): bool
    {
        return @touch($this->path($file));
    }

    public function createDirectory(string $directory): bool
    {
        return @mkdir($this->path($directory));
    }

    public function writeToFile(string $file, string $content): bool
    {
        return @file_put_contents($this->path($file), $content);
    }

    private function path(string $path): string
    {
        return $this->rootDirectory->rootDirectory() . '/' . $path;
    }
}
