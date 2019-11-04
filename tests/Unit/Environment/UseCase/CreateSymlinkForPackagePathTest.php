<?php

namespace DevMcC\PackageDev\Test\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\UseCase\CreateSymlinkForPackagePath;
use DevMcC\PackageDev\Exception\UnableToCreateBackupForPackage;
use DevMcC\PackageDev\Exception\UnableToCreateSymlinkForPackage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateSymlinkForPackagePathTest extends TestCase
{
    /** @var MockObject&FileSystem $fileSystemMock */
    private $fileSystemMock;

    /** @var CreateSymlinkForPackagePath $useCase */
    private $useCase;

    protected function setUp(): void
    {
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->useCase = new CreateSymlinkForPackagePath(
            $this->fileSystemMock
        );
    }

    public function testExecute(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;
        $expectedVendorAsLinkPath = Environment::PACKAGE_LINK_PREFIX . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($stubVendorPath, $expectedVendorAsBackupPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('linkFileAs')
            ->with($expectedVendorAsLinkPath, $stubVendorPath)
            ->willReturn(true);

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWithPackageInVendorAsLink(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertions.
        $this->fileSystemMock->expects($this->never())->method('doesDirectoryExist');
        $this->fileSystemMock->expects($this->never())->method('moveFileTo');
        $this->fileSystemMock->expects($this->never())->method('linkFileAs');

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWithPackageNotInVendorAsDirectory(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsLinkPath = Environment::PACKAGE_LINK_PREFIX . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('moveFileTo');

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('linkFileAs')
            ->with($expectedVendorAsLinkPath, $stubVendorPath)
            ->willReturn(true);

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWhenUnableToCreateBackupForPackage(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;
        $expectedVendorAsLinkPath = Environment::PACKAGE_LINK_PREFIX . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($stubVendorPath, $expectedVendorAsBackupPath)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToCreateBackupForPackage::class);
        $this->expectExceptionMessage(sprintf(UnableToCreateBackupForPackage::MESSAGE_FORMAT, $stubPackage));

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('linkFileAs');

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }

    public function testExecuteWhenUnableToCreateSymlinkForPackage(): void
    {
        $stubPackage = 'test/package';
        $stubVendorPath = '/app/vendor/test/package';

        $expectedVendorAsBackupPath = $stubVendorPath . Environment::PACKAGE_BACKUP_SUFFIX;
        $expectedVendorAsLinkPath = Environment::PACKAGE_LINK_PREFIX . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesLinkExist')
            ->with($stubVendorPath)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($stubVendorPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('moveFileTo')
            ->with($stubVendorPath, $expectedVendorAsBackupPath)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('linkFileAs')
            ->with($expectedVendorAsLinkPath, $stubVendorPath)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToCreateSymlinkForPackage::class);
        $this->expectExceptionMessage(sprintf(UnableToCreateSymlinkForPackage::MESSAGE_FORMAT, $stubPackage));

        // Starting test.
        $this->useCase->execute($stubPackage, $stubVendorPath);
    }
}
