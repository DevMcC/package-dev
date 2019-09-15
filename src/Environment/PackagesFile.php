<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Exception\EnvironmentNotInitialized;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesFile;
use DevMcC\PackageDev\Exception\UnableToReadFromPackagesFile;
use DevMcC\PackageDev\Exception\UnableToWriteToPackagesFile;

class PackagesFile
{
    private const PACKAGES_FILE_PATH = 'packages/package-dev.json';
    private const PACKAGES_KEY = 'packages';

    /**
     * @var FileSystem $fileSystem
     */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    public function isInitialized(): bool
    {
        return $this->fileSystem->doesFileExist(self::PACKAGES_FILE_PATH);
    }

    public function initialize(): void
    {
        if ($this->isInitialized()) {
            return;
        }

        if (!$this->fileSystem->createFile(self::PACKAGES_FILE_PATH)) {
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
     * @throws UnableToCreatePackagesFile
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
        $content = json_encode([self::PACKAGES_KEY => $packages], JSON_PRETTY_PRINT);

        if (!$this->fileSystem->writeToFile(self::PACKAGES_FILE_PATH, $content)) {
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
        $content = $this->fileSystem->readFromFile(self::PACKAGES_FILE_PATH);

        if (!$content) {
            throw new UnableToReadFromPackagesFile;
        }

        return json_decode($content, true)[self::PACKAGES_KEY];
    }
}
