<?php

namespace DevMcC\PackageDev\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Exception\UnableToCreateBackupForPackage;
use DevMcC\PackageDev\Exception\UnableToCreateSymlinkForPackage;

class CreateSymlinkForPackagePath
{
    /**
     * @throws UnableToCreateBackupForPackage
     * @throws UnableToCreateSymlinkForPackage
     */
    public function execute(string $package, string $vendorPath): void
    {
        if (
            $this->fileSystem->doesDirectoryExist($vendorPath)
            && !$this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->moveFileTo($vendorPath, $vendorPath . Environment::PACKAGE_BACKUP_SUFFIX)
        ) {
            throw new UnableToCreateBackupForPackage($package);
        }

        if (
            !$this->fileSystem->doesLinkExist($vendorPath)
            && !$this->fileSystem->linkFileAs(Environment::PACKAGE_LINK_PREFIX . $package, $vendorPath)
        ) {
            throw new UnableToCreateSymlinkForPackage($package);
        }
    }
}
