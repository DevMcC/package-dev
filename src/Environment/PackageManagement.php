<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Exception\PackageNotFoundInPackages;
use DevMcC\PackageDev\Exception\PackageNotFoundInVendor;
use DevMcC\PackageDev\Exception\UnableToCreateBackupForPackage;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesDirectory;
use DevMcC\PackageDev\Exception\UnableToCreateSymlinkForPackage;
use DevMcC\PackageDev\Exception\UnableToRemoveSymlinkFromPackage;
use DevMcC\PackageDev\Exception\UnableToRestorePackage;

class PackageManagement
{
    private const VENDOR_DIRECTORY_PATH = 'vendor/';
    private const PACKAGES_DIRECTORY_PATH = 'packages/';
    private const PACKAGE_BACKUP_SUFFIX = '_package-dev-bk';
    private const PACKAGE_LINK_PREFIX = '../../packages/';

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
     * @throws UnableToCreatePackagesDirectory
     */
    public function initialize(): void
    {
        if (!$this->fileSystem->doesDirectoryExist(self::PACKAGES_DIRECTORY_PATH)) {
            if (!$this->fileSystem->createDirectory(self::PACKAGES_DIRECTORY_PATH)) {
                throw new UnableToCreatePackagesDirectory;
            }
        }
    }

    public function validatePackage(string $package): void
    {
        $this->getVendorPathFromPackage($package);
    }

    /**
     * @param string[] $packages
     */
    public function createSymlinkForPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->createSymlinkForPackage($package);
        }
    }

    /**
     * @param string[] $packages
     */
    public function removeSymlinkFromPackages(array $packages): void
    {
        foreach ($packages as $package) {
            $this->removeSymlinkFromPackage($package);
        }
    }

    /**
     * @throws UnableToCreateBackupForPackage
     * @throws UnableToCreateSymlinkForPackage
     */
    public function createSymlinkForPackage(string $package): void
    {
        $vendorPath = $this->getVendorPathFromPackage($package);

        if (
            $this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->moveFileTo($vendorPath, $vendorPath . self::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new UnableToCreateBackupForPackage($package);
        }

        if (
            !$this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->linkFileAs(self::PACKAGE_LINK_PREFIX . $package, $vendorPath)
        ) {
            throw new UnableToCreateSymlinkForPackage($package);
        }
    }

    /**
     * @throws UnableToRemoveSymlinkFromPackage
     * @throws UnableToRestorePackage
     */
    public function removeSymlinkFromPackage(string $package): void
    {
        $vendorPath = $this->getVendorPathFromPackage($package);

        if (
            $this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->deleteFile($vendorPath)
        ) {
            throw new UnableToRemoveSymlinkFromPackage($package);
        }

        if (
            !$this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesLinkExist($vendorPath)
            && $this->fileSystem->doesDirectoryExist($vendorPath . self::PACKAGE_BACKUP_SUFFIX)
            && !$this->fileSystem->moveFileTo($vendorPath . self::PACKAGE_BACKUP_SUFFIX, $vendorPath)
        ) {
            throw new UnableToRestorePackage($package);
        }
    }

    /**
     * @throws PackageNotFoundInPackages
     * @throws PackageNotFoundInVendor
     */
    private function getVendorPathFromPackage(string $package): string
    {
        if (!$this->fileSystem->doesDirectoryExist(self::PACKAGES_DIRECTORY_PATH . $package)) {
            throw new PackageNotFoundInPackages($package);
        }

        $vendorPath = self::VENDOR_DIRECTORY_PATH . $package;

        if (
            !$this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesDirectoryExist($vendorPath . self::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new PackageNotFoundInVendor($package);
        }

        return $vendorPath;
    }
}
