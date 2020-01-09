<?php

namespace DevMcC\PackageDev\Test\Unit\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\UseCase\RemoveSymlinkFromPackagePath;
use DevMcC\PackageDev\Exception\FileSystem\UnableToRemoveSymlinkFromPackage;
use DevMcC\PackageDev\Exception\FileSystem\UnableToRestorePackage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RemoveSymlinkFromPackagePathTest extends TestCase
{
    /** @var MockObject&FileSystem $fileSystemMock */
    private $fileSystemMock;

    /** @var RemoveSymlinkFromPackagePath $useCase */
    private $useCase;

    protected function setUp(): void
    {
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->useCase = new RemoveSymlinkFromPackagePath(
            $this->fileSystemMock
        );
    }

    public function testExecute(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('deleteFile')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($expectedVendorAsBackupPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($expectedVendorAsBackupPath, $stubVendorPath)
            ->willReturn(true);

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWithPackageNotInVendorAsLink(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('deleteFile');

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($expectedVendorAsBackupPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($expectedVendorAsBackupPath, $stubVendorPath)
            ->willReturn(true);

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWithPackageNotInVendorAsBackup(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('deleteFile')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($expectedVendorAsBackupPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('moveFileTo');

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWhenUnableToRemoveSymlinkFromPackage(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('deleteFile')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToRemoveSymlinkFromPackage::class);
        $this->expectExceptionMessage(
            (new UnableToRemoveSymlinkFromPackage($stubPackage))->getMessage()
        );

        // Assertions.
        $this->fileSystemMock->expects($this->never())->method('doesDirectoryExist');
        $this->fileSystemMock->expects($this->never())->method('moveFileTo');

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWhenUnableToRestorePackage(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('deleteFile')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($expectedVendorAsBackupPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($expectedVendorAsBackupPath, $stubVendorPath)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToRestorePackage::class);
        $this->expectExceptionMessage(
            (new UnableToRestorePackage($stubPackage))->getMessage()
        );

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }
}
