<?php

namespace DevMcC\PackageDev\Environment;

class FileSystem
{
    /** @var RootDirectory $rootDirectory */
    private $rootDirectory;

    public function __construct(
        RootDirectory $rootDirectory
    ) {
        $this->rootDirectory = $rootDirectory;
    }

    // Exists.

    public function doesFileExist(string $file): bool
    {
        return @is_file($this->rootPathOfFile($file));
    }

    public function doesDirectoryExist(string $directory): bool
    {
        return @is_dir($this->rootPathOfFile($directory));
    }

    public function doesLinkExist(string $link): bool
    {
        return @is_link($this->rootPathOfFile($link));
    }

    // Create.

    public function createFile(string $file): bool
    {
        return @touch($this->rootPathOfFile($file));
    }

    public function createDirectory(string $directory): bool
    {
        return @mkdir($this->rootPathOfFile($directory));
    }

    // Read/Write.

    public function readFromFile(string $file): ?string
    {
        $content = @file_get_contents($this->rootPathOfFile($file));

        return $content !== false ? $content : null;
    }

    public function writeToFile(string $file, string $content): bool
    {
        return @file_put_contents($this->rootPathOfFile($file), $content);
    }

    // Delete.

    public function deleteFile(string $file): bool
    {
        return @unlink($this->rootPathOfFile($file));
    }

    // Move.

    public function moveFileTo(string $file, string $to): bool
    {
        return @rename($this->rootPathOfFile($file), $this->rootPathOfFile($to));
    }

    // Link.

    public function linkFileAs(string $file, string $as): bool
    {
        return @symlink($file, $this->rootPathOfFile($as));
    }

    // Helpers.

    public function rootPathOfFile(string $file): string
    {
        return sprintf(
            '%s/%s',
            $this->rootDirectory->rootDirectory(),
            $file
        );
    }
}
