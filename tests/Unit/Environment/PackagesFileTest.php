<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\PackagesFile;
use DevMcC\PackageDev\Exception\Environment\EnvironmentNotInitialized;
use DevMcC\PackageDev\Exception\FileSystem\UnableToCreatePackagesFile;
use DevMcC\PackageDev\Exception\FileSystem\UnableToReadFromPackagesFile;
use DevMcC\PackageDev\Exception\FileSystem\UnableToWriteToPackagesFile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PackagesFileTest extends TestCase
{
    /** @var MockObject|FileSystem $fileSystemMock */
    private $fileSystemMock;

    /** @var PackagesFile $packagesFile */
    private $packagesFile;

    protected function setUp(): void
    {
        /** @var MockObject|FileSystem */
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->packagesFile = new PackagesFile(
            $this->fileSystemMock
        );
    }

    public function testIsInitialized(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Starting test.
        $result = $this->packagesFile->isInitialized();

        // Assertion.
        $this->assertTrue($result);
    }

    public function testIsInitializedWhenFalse(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Starting test.
        $result = $this->packagesFile->isInitialized();

        // Assertion.
        $this->assertFalse($result);
    }

    public function testInitialize(): void
    {
        $expectedPackageFilesContent = json_encode([Environment::PACKAGES_KEY => []], JSON_PRETTY_PRINT);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('createFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(true);

        // Starting test.
        $this->packagesFile->initialize();
    }

    public function testInitializeWhenIsInitialized(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertions.
        $this->fileSystemMock->expects($this->never())->method('createFile');
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->initialize();
    }

    public function testInitializeWhenUnableToCreatePackagesFile(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('createFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToCreatePackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToCreatePackagesFile())->getMessage()
        );

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->initialize();
    }

    public function testInitializeWhenUnableToWriteToPackagesFile(): void
    {
        $expectedPackageFilesContent = json_encode([Environment::PACKAGES_KEY => []], JSON_PRETTY_PRINT);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('createFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToWriteToPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToWriteToPackagesFile())->getMessage()
        );

        // Starting test.
        $this->packagesFile->initialize();
    }

    public function testAddPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );
        $expectedPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test', 'test/package']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(true);

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    public function testAddPackageWithAlreadyExistingPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test', 'test/package']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    public function testAddPackageWhenEnvironmentNotInitialized(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(EnvironmentNotInitialized::class);
        $this->expectExceptionMessage(
            (new EnvironmentNotInitialized())->getMessage()
        );

        // Assertions.
        $this->fileSystemMock->expects($this->never())->method('readFromFile');
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    public function testAddPackageWhenUnableToReadFromPackagesFile(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(null);

        // Assert exception.
        $this->expectException(UnableToReadFromPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToReadFromPackagesFile())->getMessage()
        );

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    public function testAddPackageWhenUnableToWriteToPackagesFile(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );
        $expectedPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test', 'test/package']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToWriteToPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToWriteToPackagesFile())->getMessage()
        );

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    /**
     * The only reason why JSON would be invalid is with a bad package name, but that gets validated by PackageArgument
     * before it even gets here, so you will probably never have invalid JSON. The check is purely there to satisfy
     * PHPStan... and this here is to test that satisfactory check. The way this is gonna be tested is to just add a
     * package that could only be created by Cthulu.
     */
    public function testAddPackageWhenUnableToWriteToPackagesFileDueToInvalidJSON(): void
    {
        $stubPackageName = mb_convert_encoding('tëst/package', 'Latin1', 'UTF8');
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assert exception.
        $this->expectException(UnableToWriteToPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToWriteToPackagesFile())->getMessage()
        );

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->addPackage($stubPackageName);
    }

    public function testRemovePackage(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['test/package', 'package/test']],
            JSON_PRETTY_PRINT
        );
        $expectedPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(true);

        // Starting test.
        $this->packagesFile->removePackage($stubPackageName);
    }

    public function testRemovePackageWithAlreadyRemovedPackage(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->removePackage($stubPackageName);
    }

    public function testRemovePackageWhenEnvironmentNotInitialized(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(EnvironmentNotInitialized::class);
        $this->expectExceptionMessage(
            (new EnvironmentNotInitialized())->getMessage()
        );

        // Assertions.
        $this->fileSystemMock->expects($this->never())->method('readFromFile');
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->removePackage($stubPackageName);
    }

    public function testRemovePackageWhenUnableToReadFromPackagesFile(): void
    {
        $stubPackageName = 'test/package';

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(null);

        // Assert exception.
        $this->expectException(UnableToReadFromPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToReadFromPackagesFile())->getMessage()
        );

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('writeToFile');

        // Starting test.
        $this->packagesFile->removePackage($stubPackageName);
    }

    public function testRemovePackageWhenUnableToWriteToPackagesFile(): void
    {
        $stubPackageName = 'test/package';
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['test/package', 'package/test']],
            JSON_PRETTY_PRINT
        );
        $expectedPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => ['package/test']],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('writeToFile')
            ->with(Environment::PACKAGES_FILE_PATH, $expectedPackageFilesContent)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(UnableToWriteToPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToWriteToPackagesFile())->getMessage()
        );

        // Starting test.
        $this->packagesFile->removePackage($stubPackageName);
    }

    public function testGetPackages(): void
    {
        $stubPackages = ['test/package', 'package/test'];
        $stubPackageFilesContent = json_encode(
            [Environment::PACKAGES_KEY => $stubPackages],
            JSON_PRETTY_PRINT
        );

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn($stubPackageFilesContent);

        // Starting test.
        $result = $this->packagesFile->getPackages();

        // Assertion.
        $this->assertSame($stubPackages, $result);
    }

    public function testGetPackagesWhenEnvironmentNotInitialized(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(false);

        // Assert exception.
        $this->expectException(EnvironmentNotInitialized::class);
        $this->expectExceptionMessage(
            (new EnvironmentNotInitialized())->getMessage()
        );

        // Assertion.
        $this->fileSystemMock->expects($this->never())->method('readFromFile');

        // Starting test.
        $this->packagesFile->getPackages();
    }

    public function testGetPackagesWhenUnableToReadFromPackagesFile(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(true);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('readFromFile')
            ->with(Environment::PACKAGES_FILE_PATH)
            ->willReturn(null);

        // Assert exception.
        $this->expectException(UnableToReadFromPackagesFile::class);
        $this->expectExceptionMessage(
            (new UnableToReadFromPackagesFile())->getMessage()
        );

        // Starting test.
        $this->packagesFile->getPackages();
    }
}
