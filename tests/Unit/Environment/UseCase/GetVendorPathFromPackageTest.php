<?php

namespace DevMcC\PackageDev\Test\Environment\UseCase;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\UseCase\GetVendorPathFromPackage;
use DevMcC\PackageDev\Exception\PackageNotFoundInPackages;
use DevMcC\PackageDev\Exception\PackageNotFoundInVendor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetVendorPathFromPackageTest extends TestCase
{
    /** @var MockObject&FileSystem $fileSystemMock */
    private $fileSystemMock;

    /** @var GetVendorPathFromPackage $useCase */
    private $useCase;

    public function setUp(): void
    {
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->useCase = new GetVendorPathFromPackage(
            $this->fileSystemMock
        );
    }

    public function testExecute(): void
    {
        $stubPackage = 'test/package';

        $expectedPackagePath = Environment::PACKAGES_DIRECTORY_PATH . $stubPackage;
        $expectedVendorAsDirectoryPath = Environment::VENDOR_DIRECTORY_PATH . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->exactly(2))
            ->method('doesDirectoryExist')
            ->withConsecutive(
                [$expectedPackagePath],
                [$expectedVendorAsDirectoryPath]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                true
            );

        // Starting test.
        $result = $this->useCase->execute($stubPackage);

        // Assertion.
        $this->assertSame($expectedVendorAsDirectoryPath, $result);
    }

    public function testExecuteWithPackageInVendorAsBackup(): void
    {
        $stubPackage = 'test/package';

        $expectedPackagePath = Environment::PACKAGES_DIRECTORY_PATH . $stubPackage;
        $expectedVendorAsDirectoryPath = Environment::VENDOR_DIRECTORY_PATH . $stubPackage;
        $expectedVendorAsBackupPath = $expectedVendorAsDirectoryPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->exactly(3))
            ->method('doesDirectoryExist')
            ->withConsecutive(
                [$expectedPackagePath],
                [$expectedVendorAsDirectoryPath],
                [$expectedVendorAsBackupPath]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                false,
                true
            );

        // Starting test.
        $result = $this->useCase->execute($stubPackage);

        // Assertion.
        $this->assertSame($expectedVendorAsDirectoryPath, $result);
    }

    public function testExecuteWhenPackageNotFoundInPackages(): void
    {
        $stubPackage = 'test/package';

        $expectedPackagePath = Environment::PACKAGES_DIRECTORY_PATH . $stubPackage;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with($expectedPackagePath)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(PackageNotFoundInPackages::class);
        $this->expectExceptionMessage(sprintf(PackageNotFoundInPackages::MESSAGE_FORMAT, $stubPackage));

        // Starting test.
        $this->useCase->execute($stubPackage);
    }

    public function testExecuteWhenPackageNotFoundInVendor(): void
    {
        $stubPackage = 'test/package';

        $expectedPackagePath = Environment::PACKAGES_DIRECTORY_PATH . $stubPackage;
        $expectedVendorAsDirectoryPath = Environment::VENDOR_DIRECTORY_PATH . $stubPackage;
        $expectedVendorAsBackupPath = $expectedVendorAsDirectoryPath . Environment::PACKAGE_BACKUP_SUFFIX;

        // Assertion.
        $this->fileSystemMock
            ->expects($this->exactly(3))
            ->method('doesDirectoryExist')
            ->withConsecutive(
                [$expectedPackagePath],
                [$expectedVendorAsDirectoryPath],
                [$expectedVendorAsBackupPath]
            )
            ->willReturnOnConsecutiveCalls(
                true,
                false,
                false
            );

        // Assert exception.
        $this->expectException(PackageNotFoundInVendor::class);
        $this->expectExceptionMessage(sprintf(PackageNotFoundInVendor::MESSAGE_FORMAT, $stubPackage));

        // Starting test.
        $this->useCase->execute($stubPackage);
    }
}
