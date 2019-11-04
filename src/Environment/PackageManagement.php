<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\UseCase\CreateSymlinkForPackagePath;
use DevMcC\PackageDev\Environment\UseCase\GetVendorPathFromPackage;
use DevMcC\PackageDev\Environment\UseCase\RemoveSymlinkFromPackagePath;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesDirectory;

class PackageManagement
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;
    /** @var GetVendorPathFromPackage $getVendorPathFromPackage */
    private $getVendorPathFromPackage;
    /** @var CreateSymlinkForPackagePath $createSymlinkForPackagePath */
    private $createSymlinkForPackagePath;
    /** @var RemoveSymlinkFromPackagePath $removeSymlinkFromPackagePath */
    private $removeSymlinkFromPackagePath;

    public function __construct(
        FileSystem $fileSystem,
        GetVendorPathFromPackage $getVendorPathFromPackage,
        CreateSymlinkForPackagePath $createSymlinkForPackagePath,
        RemoveSymlinkFromPackagePath $removeSymlinkFromPackagePath
    ) {
        $this->fileSystem = $fileSystem;
        $this->getVendorPathFromPackage = $getVendorPathFromPackage;
        $this->createSymlinkForPackagePath = $createSymlinkForPackagePath;
        $this->removeSymlinkFromPackagePath = $removeSymlinkFromPackagePath;
    }

    /**
     * @throws UnableToCreatePackagesDirectory
     */
    public function initialize(): void
    {
        if (!$this->fileSystem->doesDirectoryExist(Environment::PACKAGES_DIRECTORY_PATH)) {
            if (!$this->fileSystem->createDirectory(Environment::PACKAGES_DIRECTORY_PATH)) {
                throw new UnableToCreatePackagesDirectory;
            }
        }
    }

    public function validatePackage(string $package): void
    {
        $this->getVendorPathFromPackage->execute($package);
    }

    public function createSymlinkForPackage(string $package): void
    {
        $vendorPath = $this->getVendorPathFromPackage->execute($package);

        $this->createSymlinkForPackagePath->execute($package, $vendorPath);
    }

    public function removeSymlinkFromPackage(string $package): void
    {
        $vendorPath = $this->getVendorPathFromPackage->execute($package);

        $this->removeSymlinkFromPackagePath->execute($package, $vendorPath);
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
}
