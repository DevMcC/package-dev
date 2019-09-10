<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Exception\EnvironmentNotInitialized;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesDirectory;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesFile;
use DevMcC\PackageDev\Exception\UnableToWriteToPackagesFile;

class Environment
{
    private const PACKAGES_DIRECTORY_PATH = 'packages/';
    private const PACKAGES_FILE_PATH = 'packages/package-dev.json';

    /**
     * @var FileSystem $fileSystem
     */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @throws EnvironmentNotInitialized
     */
    public function throwIfNotInitialized(): void
    {
        if (!$this->isInitialized()) {
            throw new EnvironmentNotInitialized;
        }
    }

    public function initialize(): bool
    {
        if ($this->isInitialized()) {
            return false;
        }

        if (!$this->fileSystem->doesDirectoryExist(self::PACKAGES_DIRECTORY_PATH)) {
            if (!$this->fileSystem->createDirectory(self::PACKAGES_DIRECTORY_PATH)) {
                throw new UnableToCreatePackagesDirectory;
            }
        }

        if (!$this->fileSystem->createFile(self::PACKAGES_FILE_PATH)) {
            throw new UnableToCreatePackagesFile;
        }

        $this->writePackagesFile([]);

        return true;
    }

    private function isInitialized(): bool
    {
        return $this->fileSystem->doesFileExist(self::PACKAGES_FILE_PATH);
    }

    private function writePackagesFile(array $packages): void
    {
        $content = json_encode(['packages' => $packages], JSON_PRETTY_PRINT);

        if (!$this->fileSystem->writeToFile(self::PACKAGES_FILE_PATH, $content)) {
            throw new UnableToWriteToPackagesFile;
        }
    }
}
