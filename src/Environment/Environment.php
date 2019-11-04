<?php

namespace DevMcC\PackageDev\Environment;

class Environment
{
    public const PACKAGES_FILE_PATH = 'packages/package-dev.json';
    public const PACKAGES_KEY = 'packages';

    public const PACKAGES_DIRECTORY_PATH = 'packages/';
    public const PACKAGE_BACKUP_SUFFIX = '_package-dev-bk';
    public const PACKAGE_LINK_PREFIX = '../../packages/';
    public const VENDOR_DIRECTORY_PATH = 'vendor/';

    /** @var PackageManagement $packageManagement */
    private $packageManagement;
    /** @var PackagesFile $packagesFile */
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
