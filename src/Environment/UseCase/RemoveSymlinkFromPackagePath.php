<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Exception\FileSystem\UnableToRemoveSymlinkFromPackage;
use DevMcC\PackageDev\Exception\FileSystem\UnableToRestorePackage;

class RemoveSymlinkFromPackagePath
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @throws UnableToRemoveSymlinkFromPackage
     * @throws UnableToRestorePackage
     */
    public function execute(string $package, string $vendorPath): void
    {
        if (
            $this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->deleteFile($vendorPath)
        ) {
            throw new UnableToRemoveSymlinkFromPackage($package);
        }

        if (
            $this->fileSystem->doesDirectoryExist($vendorPath . Environment::PACKAGE_BACKUP_SUFFIX)
            && !$this->fileSystem->moveFileTo($vendorPath . Environment::PACKAGE_BACKUP_SUFFIX, $vendorPath)
        ) {
            throw new UnableToRestorePackage($package);
        }
    }
}
