<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\PackageManagement;
use DevMcC\PackageDev\Environment\UseCase\CreateSymlinkForPackagePath;
use DevMcC\PackageDev\Environment\UseCase\GetVendorPathFromPackage;
use DevMcC\PackageDev\Environment\UseCase\RemoveSymlinkFromPackagePath;
use DevMcC\PackageDev\Exception\PackageNotFoundInPackages;
use DevMcC\PackageDev\Exception\PackageNotFoundInVendor;
use DevMcC\PackageDev\Exception\UnableToCreateBackupForPackage;
use DevMcC\PackageDev\Exception\UnableToCreatePackagesDirectory;
use DevMcC\PackageDev\Exception\UnableToCreateSymlinkForPackage;
use DevMcC\PackageDev\Exception\UnableToRemoveSymlinkFromPackage;
use DevMcC\PackageDev\Exception\UnableToRestorePackage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PackageManagementTest extends TestCase
{
    /** @var MockObject&FileSystem $fileSystemMock */
    private $fileSystemMock;
    /** @var MockObject&GetVendorPathFromPackage $getVendorPathFromPackageMock */
    private $getVendorPathFromPackageMock;
    /** @var MockObject&CreateSymlinkForPackagePath $createSymlinkForPackagePathMock */
    private $createSymlinkForPackagePathMock;
    /** @var MockObject&RemoveSymlinkFromPackagePath $removeSymlinkFromPackagePathMock */
    private $removeSymlinkFromPackagePathMock;

    /** @var PackageManagement $packageManagement */
    private $packageManagement;

    protected function setUp(): void
    {
        $this->fileSystemMock = $this->createMock(FileSystem::class);
        $this->getVendorPathFromPackageMock = $this->createMock(GetVendorPathFromPackage::class);
        $this->createSymlinkForPackagePathMock = $this->createMock(CreateSymlinkForPackagePath::class);
        $this->removeSymlinkFromPackagePathMock = $this->createMock(RemoveSymlinkFromPackagePath::class);

        $this->packageManagement = new PackageManagement(
            $this->fileSystemMock,
            $this->getVendorPathFromPackageMock,
            $this->createSymlinkForPackagePathMock,
            $this->removeSymlinkFromPackagePathMock
        );
    }

    public function testInitialize(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with(Environment::PACKAGES_DIRECTORY_PATH)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('createDirectory')
            ->with(Environment::PACKAGES_DIRECTORY_PATH)
            ->willReturn(true);

        // Starting test.
        $this->packageManagement->initialize();
    }

    public function testInitializeWhenDirectoryDoesExist(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with(Environment::PACKAGES_DIRECTORY_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('createDirectory');

        // Starting test.
        $this->packageManagement->initialize();
    }

    public function testInitializeWhenUnableToCreatePackagesDirectory(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesDirectoryExist')
            ->with(Environment::PACKAGES_DIRECTORY_PATH)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('createDirectory')
            ->with(Environment::PACKAGES_DIRECTORY_PATH)
            ->willReturn(false);

        // Assert Exception.
        $this->expectException(UnableToCreatePackagesDirectory::class);
        $this->expectExceptionMessage(UnableToCreatePackagesDirectory::MESSAGE);

        // Starting test.
        $this->packageManagement->initialize();
    }

    public function testValidatePackage(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName);

        // Starting test.
        $this->packageManagement->validatePackage($stubPackageName);
    }

    public function testValidatePackageWhenPackageNotFoundInPackages(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->will(
                $this->throwException(new PackageNotFoundInPackages($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(PackageNotFoundInPackages::class);
        $this->expectExceptionMessage(
            sprintf(PackageNotFoundInPackages::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->validatePackage($stubPackageName);
    }

    public function testValidatePackageWhenPackageNotFoundInVendor(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->will(
                $this->throwException(new PackageNotFoundInVendor($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(PackageNotFoundInVendor::class);
        $this->expectExceptionMessage(
            sprintf(PackageNotFoundInVendor::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->validatePackage($stubPackageName);
    }

    public function testCreateSymlinkForPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath);

        // Starting test.
        $this->packageManagement->createSymlinkForPackage($stubPackageName);
    }

    public function testCreateSymlinkForPackageWhenUnableToCreateBackupForPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath)
            ->will(
                $this->throwException(new UnableToCreateBackupForPackage($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(UnableToCreateBackupForPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToCreateBackupForPackage::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->createSymlinkForPackage($stubPackageName);
    }

    public function testCreateSymlinkForPackageWhenUnableToCreateSymlinkForPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath)
            ->will(
                $this->throwException(new UnableToCreateSymlinkForPackage($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(UnableToCreateSymlinkForPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToCreateSymlinkForPackage::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->createSymlinkForPackage($stubPackageName);
    }

    public function testRemoveSymlinkFromPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath);

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackage($stubPackageName);
    }

    public function testRemoveSymlinkFromPackageWhenUnableToRemoveSymlinkFromPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath)
            ->will(
                $this->throwException(new UnableToRemoveSymlinkFromPackage($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(UnableToRemoveSymlinkFromPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToRemoveSymlinkFromPackage::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackage($stubPackageName);
    }

    public function testRemoveSymlinkFromPackageWhenUnableToRestorePackage(): void
    {
        $stubPackageName = 'test/package';
        $stubVendorPath = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName)
            ->willReturn($stubVendorPath);

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageName, $stubVendorPath)
            ->will(
                $this->throwException(new UnableToRestorePackage($stubPackageName))
            );

        // Assert Exception.
        $this->expectException(UnableToRestorePackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToRestorePackage::MESSAGE_FORMAT, $stubPackageName)
        );

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackage($stubPackageName);
    }

    public function testCreateSymlinkForPackages(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths = [
            'path/to/vendor/test/package',
            'path/to/vendor/testing/test',
            'path/to/vendor/package/test',
        ];

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->exactly(3))
            ->method('execute')
            ->withConsecutive(
                [$stubPackageNames[0]],
                [$stubPackageNames[1]],
                [$stubPackageNames[2]]
            )
            ->willReturnOnConsecutiveCalls(
                $stubVendorPaths[0],
                $stubVendorPaths[1],
                $stubVendorPaths[2]
            );

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->exactly(3))
            ->method('execute')
            ->withConsecutive(
                [$stubPackageNames[0], $stubVendorPaths[0]],
                [$stubPackageNames[1], $stubVendorPaths[1]],
                [$stubPackageNames[2], $stubVendorPaths[2]]
            );

        // Starting test.
        $this->packageManagement->createSymlinkForPackages($stubPackageNames);
    }

    public function testCreateSymlinkForPackagesWhenUnableToCreateBackupForPackage(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths0 = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0])
            ->willReturn($stubVendorPaths0);

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0], $stubVendorPaths0)
            ->will(
                $this->throwException(new UnableToCreateBackupForPackage($stubPackageNames[0]))
            );

        // Assert Exception.
        $this->expectException(UnableToCreateBackupForPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToCreateBackupForPackage::MESSAGE_FORMAT, $stubPackageNames[0])
        );

        // Starting test.
        $this->packageManagement->createSymlinkForPackages($stubPackageNames);
    }

    public function testCreateSymlinkForPackagesWhenUnableToCreateSymlinkForPackage(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths0 = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0])
            ->willReturn($stubVendorPaths0);

        // Assertion.
        $this->createSymlinkForPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0], $stubVendorPaths0)
            ->will(
                $this->throwException(new UnableToCreateSymlinkForPackage($stubPackageNames[0]))
            );

        // Assert Exception.
        $this->expectException(UnableToCreateSymlinkForPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToCreateSymlinkForPackage::MESSAGE_FORMAT, $stubPackageNames[0])
        );

        // Starting test.
        $this->packageManagement->createSymlinkForPackages($stubPackageNames);
    }

    public function testRemoveSymlinkFromPackages(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths = [
            'path/to/vendor/test/package',
            'path/to/vendor/testing/test',
            'path/to/vendor/package/test',
        ];

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->exactly(3))
            ->method('execute')
            ->withConsecutive(
                [$stubPackageNames[0]],
                [$stubPackageNames[1]],
                [$stubPackageNames[2]]
            )
            ->willReturnOnConsecutiveCalls(
                $stubVendorPaths[0],
                $stubVendorPaths[1],
                $stubVendorPaths[2]
            );

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->exactly(3))
            ->method('execute')
            ->withConsecutive(
                [$stubPackageNames[0], $stubVendorPaths[0]],
                [$stubPackageNames[1], $stubVendorPaths[1]],
                [$stubPackageNames[2], $stubVendorPaths[2]]
            );

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackages($stubPackageNames);
    }

    public function testRemoveSymlinkFromPackagesWhenUnableToRemoveSymlinkFromPackage(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths0 = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0])
            ->willReturn($stubVendorPaths0);

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0], $stubVendorPaths0)
            ->will(
                $this->throwException(new UnableToRemoveSymlinkFromPackage($stubPackageNames[0]))
            );

        // Assert Exception.
        $this->expectException(UnableToRemoveSymlinkFromPackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToRemoveSymlinkFromPackage::MESSAGE_FORMAT, $stubPackageNames[0])
        );

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackages($stubPackageNames);
    }

    public function testRemoveSymlinkFromPackagesWhenUnableToRestorePackage(): void
    {
        $stubPackageNames = [
            'test/package',
            'testing/test',
            'package/test',
        ];
        $stubVendorPaths0 = 'path/to/vendor/test/package';

        // Assertion.
        $this->getVendorPathFromPackageMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0])
            ->willReturn($stubVendorPaths0);

        // Assertion.
        $this->removeSymlinkFromPackagePathMock
            ->expects($this->once())
            ->method('execute')
            ->with($stubPackageNames[0], $stubVendorPaths0)
            ->will(
                $this->throwException(new UnableToRestorePackage($stubPackageNames[0]))
            );

        // Assert Exception.
        $this->expectException(UnableToRestorePackage::class);
        $this->expectExceptionMessage(
            sprintf(UnableToRestorePackage::MESSAGE_FORMAT, $stubPackageNames[0])
        );

        // Starting test.
        $this->packageManagement->removeSymlinkFromPackages($stubPackageNames);
    }
}
