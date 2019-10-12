<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Exception\PackageNotFoundInPackages;
use DevMcC\PackageDev\Exception\PackageNotFoundInVendor;

class GetVendorPathFromPackage
{
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
     * @throws PackageNotFoundInPackages
     * @throws PackageNotFoundInVendor
     */
    public function execute(string $package): string
    {
        if (!$this->fileSystem->doesDirectoryExist(Environment::PACKAGES_DIRECTORY_PATH . $package)) {
            throw new PackageNotFoundInPackages($package);
        }

        $vendorPath = self::VENDOR_DIRECTORY_PATH . $package;

        if (
            !$this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesDirectoryExist($vendorPath . Environment::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new PackageNotFoundInVendor($package);
        }

        return $vendorPath;
    }
}
