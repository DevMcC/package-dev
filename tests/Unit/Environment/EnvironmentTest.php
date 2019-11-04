<?php

namespace DevMcC\PackageDev\Test\Environment;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\PackageManagement;
use DevMcC\PackageDev\Environment\PackagesFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    /** @var MockObject&PackageManagement $packageManagementMock */
    private $packageManagementMock;
    /** @var MockObject&PackagesFile $packagesFileMock */
    private $packagesFileMock;

    /** @var Environment $environment */
    private $environment;

    protected function setUp(): void
    {
        $this->packageManagementMock = $this->createMock(PackageManagement::class);
        $this->packagesFileMock = $this->createMock(PackagesFile::class);

        $this->environment = new Environment(
            $this->packageManagementMock,
            $this->packagesFileMock
        );
    }

    public function testInitialize(): void
    {
        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('isInitialized')
            ->willReturn(false);

        // Assertions.
        $this->packageManagementMock->expects($this->once())->method('initialize');
        $this->packagesFileMock->expects($this->once())->method('initialize');

        // Starting test.
        $result = $this->environment->initialize();

        // Assertion.
        $this->assertTrue($result);
    }

    public function testInitializeWhenAlreadyInitiated(): void
    {
        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);

        // Assertions.
        $this->packageManagementMock->expects($this->never())->method('initialize');
        $this->packagesFileMock->expects($this->never())->method('initialize');

        // Starting test.
        $result = $this->environment->initialize();

        // Assertion.
        $this->assertFalse($result);
    }

    public function testLink(): void
    {
        $stubPackage = 'test/package';

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('validatePackage')
            ->with($stubPackage);

        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('addPackage')
            ->with($stubPackage);

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('createSymlinkForPackage')
            ->with($stubPackage);

        // Starting test.
        $this->environment->link($stubPackage);
    }

    public function testUnlink(): void
    {
        $stubPackage = 'test/package';

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('validatePackage')
            ->with($stubPackage);

        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('removePackage')
            ->with($stubPackage);

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('removeSymlinkFromPackage')
            ->with($stubPackage);

        // Starting test.
        $this->environment->unlink($stubPackage);
    }

    public function testCreateSymlinks(): void
    {
        $stubPackages = ['test/package'];

        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('getPackages')
            ->willReturn($stubPackages);

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('createSymlinkForPackages')
            ->with($stubPackages);

        // Starting test.
        $this->environment->createSymlinks();
    }

    public function testRemoveSymlinks(): void
    {
        $stubPackages = ['test/package'];

        // Assertion.
        $this->packagesFileMock
            ->expects($this->once())
            ->method('getPackages')
            ->willReturn($stubPackages);

        // Assertion.
        $this->packageManagementMock
            ->expects($this->once())
            ->method('removeSymlinkFromPackages')
            ->with($stubPackages);

        // Starting test.
        $this->environment->removeSymlinks();
    }
}
