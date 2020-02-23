<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Exception\FileSystem\PackageNotFoundInPackages;
use DevMcC\PackageDev\Exception\FileSystem\PackageNotFoundInVendor;

class GetVendorPathFromPackage
{
    /** @var FileSystem $fileSystem */
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

        $vendorPath = Environment::VENDOR_DIRECTORY_PATH . $package;

        if (
            !$this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesDirectoryExist($vendorPath . Environment::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new PackageNotFoundInVendor($package);
        }

        return $vendorPath;
    }
}
