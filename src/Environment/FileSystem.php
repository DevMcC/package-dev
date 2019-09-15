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

    // Exists.

    public function doesFileExist(string $file): bool
    {
        return is_file($this->path($file));
    }

    public function doesDirectoryExist(string $directory): bool
    {
        return is_dir($this->path($directory));
    }

    public function doesLinkExist(string $link): bool
    {
        return is_link($link);
    }

    // Create.

    public function createFile(string $file): bool
    {
        return @touch($this->path($file));
    }

    public function createDirectory(string $directory): bool
    {
        return @mkdir($this->path($directory));
    }

    // Read/Write.

    public function readFromFile(string $file): ?string
    {
        $content = @file_get_contents($this->path($file));

        return $content !== false ? $content : null;
    }

    public function writeToFile(string $file, string $content): bool
    {
        return @file_put_contents($this->path($file), $content);
    }

    // Delete.

    public function deleteFile(string $file): bool
    {
        return @unlink($this->path($file));
    }

    // Move.

    public function moveFileTo(string $file, string $to): bool
    {
        return @rename($file, $to);
    }

    // Link.

    public function linkFileAs(string $file, string $link): bool
    {
        return @symlink($file, $this->path($link));
    }

    // Helpers.

    private function path(string $path): string
    {
        return $this->rootDirectory->rootDirectory() . '/' . $path;
    }
}