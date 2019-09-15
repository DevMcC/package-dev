<?php

namespace DevMcC\PackageDev\Environment;

class Environment
{

    /**
     * @var PackageManagement $packageManagement
     * @var PackagesFile $packagesFile
     */
    private $packageManagement;
    private $packagesFile;

    public function __construct(
        PackageManagement $packageManagement,
        PackagesFile $packagesFile
    ) {
        $this->packageManagement = $packageManagement;
        $this->packagesFile = $packagesFile;
    }

    public function initialize(): bool
    {
        if ($this->packagesFile->isInitialized()) {
            return false;
        }

        $this->packageManagement->initialize();
        $this->packagesFile->initialize();

        return true;
    }

    public function link(string $package): void
    {
        $this->packageManagement->validatePackage($package);
        $this->packagesFile->addPackage($package);
        $this->packageManagement->createSymlinkForPackage($package);
    }

    public function unlink(string $package): void
    {
        $this->packageManagement->validatePackage($package);
        $this->packagesFile->removePackage($package);
        $this->packageManagement->removeSymlinkFromPackage($package);
    }

    public function createSymlinks(): void
    {
        $this->packageManagement->createSymlinkForPackages(
            $this->packagesFile->getPackages()
        );
    }

    public function removeSymlinks(): void
    {
        $this->packageManagement->removeSymlinkFromPackages(
            $this->packagesFile->getPackages()
        );
    }
}
