<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Exception\Environment\EnvironmentNotInitialized;
use DevMcC\PackageDev\Exception\FileSystem\UnableToCreatePackagesFile;
use DevMcC\PackageDev\Exception\FileSystem\UnableToReadFromPackagesFile;
use DevMcC\PackageDev\Exception\FileSystem\UnableToWriteToPackagesFile;

class PackagesFile
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    public function isInitialized(): bool
    {
        return $this->fileSystem->doesFileExist(Environment::PACKAGES_FILE_PATH);
    }

    /**
     * @throws UnableToCreatePackagesFile
     */
    public function initialize(): void
    {
        if ($this->isInitialized()) {
            return;
        }

        if (!$this->fileSystem->createFile(Environment::PACKAGES_FILE_PATH)) {
            throw new UnableToCreatePackagesFile;
        }

        $this->write([]);
    }

    public function addPackage(string $package): void
    {
        $this->validateInitialization();

        $packages = $this->read();

        if (array_search($package, $packages) === false) {
            $packages[] = $package;

            $this->write($packages);
        }
    }

    public function removePackage(string $package): void
    {
        $this->validateInitialization();

        $packages = $this->read();
        $packageIndex = array_search($package, $packages);

        if ($packageIndex !== false) {
            array_splice($packages, $packageIndex, 1);

            $this->write($packages);
        }
    }

    /**
     * @return string[]
     */
    public function getPackages(): array
    {
        $this->validateInitialization();

        return $this->read();
    }

    /**
     * @throws EnvironmentNotInitialized
     */
    private function validateInitialization(): void
    {
        if (!$this->isInitialized()) {
            throw new EnvironmentNotInitialized;
        }
    }

    /**
     * @throws UnableToWriteToPackagesFile
     *
     * @param string[] $packages
     */
    private function write(array $packages): void
    {
        $content = json_encode([Environment::PACKAGES_KEY => $packages], JSON_PRETTY_PRINT);

        if (!$this->fileSystem->writeToFile(Environment::PACKAGES_FILE_PATH, $content)) {
            throw new UnableToWriteToPackagesFile;
        }
    }

    /**
     * @throws UnableToReadFromPackagesFile
     *
     * @return string[]
     */
    private function read(): array
    {
        $content = $this->fileSystem->readFromFile(Environment::PACKAGES_FILE_PATH);

        if (is_null($content)) {
            throw new UnableToReadFromPackagesFile;
        }

        return json_decode($content, true)[Environment::PACKAGES_KEY];
    }
}
