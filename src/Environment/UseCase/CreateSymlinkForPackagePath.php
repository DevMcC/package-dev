<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Exception\FileSystem\UnableToCreateBackupForPackage;
use DevMcC\PackageDev\Exception\FileSystem\UnableToCreateSymlinkForPackage;

class CreateSymlinkForPackagePath
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @throws UnableToCreateBackupForPackage
     * @throws UnableToCreateSymlinkForPackage
     */
    public function execute(string $package, string $vendorPath): void
    {
        if ($this->fileSystem->doesLinkExist($vendorPath)) {
            return;
        }

        if (
            $this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->moveFileTo($vendorPath, $vendorPath . Environment::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new UnableToCreateBackupForPackage($package);
        }

        if (
            !$this->fileSystem->linkFileAs(Environment::PACKAGE_LINK_PREFIX . $package, $vendorPath)
        ) {
            throw new UnableToCreateSymlinkForPackage($package);
        }
    }
}
