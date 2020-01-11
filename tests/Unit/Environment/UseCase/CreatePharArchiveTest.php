<?php

namespace DevMcC\PackageDev\Test\Unit\Environment\UseCase;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\UseCase\CreatePharArchive;
use DevMcC\PackageDev\Test\Unit\FileSystemTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Phar;

class CreatePharArchiveTest extends FileSystemTestCase
{
    /** @var MockObject|FileSystem $fileSystemMock */
    private $fileSystemMock;

    /** @var CreatePharArchive $useCase */
    private $useCase;

    protected function setUp(): void
    {
        $this->fileSystemMock = $this->createMock(FileSystem::class);

        $this->useCase = new CreatePharArchive(
            $this->fileSystemMock
        );
    }

    public function testExecute(): void
    {
        $stubPath = $this->pathForFile(false) . '.phar';
        $stubFname = $this->basename($stubPath);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('rootPathOfFile')
            ->with($stubFname)
            ->willReturn($stubPath);

        // Starting test.
        $result = $this->useCase->execute($stubFname);

        // Assertion.
        $this->assertInstanceOf(Phar::class, $result);
        $this->assertSame($stubPath, $result->getPath());
    }

    protected function randomPath(string $prefix): string
    {
        return parent::randomPath($prefix . '__cpat');
    }
}
