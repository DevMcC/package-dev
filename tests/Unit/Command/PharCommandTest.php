<?php

namespace DevMcC\PackageDev\Test\Unit\Command;

use DevMcC\PackageDev\Command\PharCommand;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Environment\UseCase\CreatePharArchive;
use DevMcC\PackageDev\Exception\Environment\PharArchiveAlreadyExists;
use DevMcC\PackageDev\Exception\Environment\UnableToCreatePharArchive;
use Phar;
use PharException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PharCommandTest extends TestCase
{
    /** @var MockObject|CreatePharArchive $createPharArchiveMock */
    private $createPharArchiveMock;
    /** @var MockObject|FileSystem $fileSystemMock */
    private $fileSystemMock;
    /** @var MockObject|RootDirectory $rootDirectoryMock */
    private $rootDirectoryMock;
    /** @var MockObject|Output $outputMock */
    private $outputMock;

    /** @var PharCommand $command */
    private $command;

    protected function setUp(): void
    {
        /** @var MockObject|CreatePharArchive */
        $this->createPharArchiveMock = $this->createMock(CreatePharArchive::class);
        /** @var MockObject|FileSystem */
        $this->fileSystemMock = $this->createMock(FileSystem::class);
        /** @var MockObject|RootDirectory */
        $this->rootDirectoryMock = $this->createMock(RootDirectory::class);
        /** @var MockObject|Output */
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new PharCommand(
            $this->createPharArchiveMock,
            $this->fileSystemMock,
            $this->rootDirectoryMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        $stubBaseDir = 'testing';
        $stubDefaultStub = 'test123';

        $pharMock = $this->createMock(Phar::class);

        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PHAR_ARCHIVE_NAME)
            ->willReturn(false);

        // Assertion.
        $this->createPharArchiveMock
            ->expects($this->once())
            ->method('execute')
            ->with(Environment::PHAR_ARCHIVE_NAME)
            ->willReturn($pharMock);

        // Assertion.
        $this->rootDirectoryMock
            ->expects($this->once())
            ->method('packageDevSrcDirectory')
            ->willReturn($stubBaseDir);

        // Assertions.
        $pharMock->expects($this->once())->method('startBuffering');
        $pharMock->expects($this->once())->method('buildFromDirectory')->with($stubBaseDir);
        $pharMock->expects($this->once())->method('getStub')->willReturn($stubDefaultStub);
        $pharMock->expects($this->once())->method('setStub')->with("#!/usr/bin/env php\n" . $stubDefaultStub);
        $pharMock->expects($this->once())->method('stopBuffering');

        // Starting test.
        $this->command->handle();
    }

    public function testHandleWhenPharArchiveAlreadyExists(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PHAR_ARCHIVE_NAME)
            ->willReturn(true);

        // Assert exception.
        $this->expectException(PharArchiveAlreadyExists::class);
        $this->expectExceptionMessage(
            (new PharArchiveAlreadyExists(Environment::PHAR_ARCHIVE_NAME))->getMessage()
        );

        // Assertions.
        $this->createPharArchiveMock->expects($this->never())->method('execute');
        $this->rootDirectoryMock->expects($this->never())->method('packageDevSrcDirectory');

        // Starting test.
        $this->command->handle();
    }

    public function testHandleWhenUnableToCreatePharArchive(): void
    {
        // Assertion.
        $this->fileSystemMock
            ->expects($this->once())
            ->method('doesFileExist')
            ->with(Environment::PHAR_ARCHIVE_NAME)
            ->willReturn(false);

        // Assertion.
        $this->createPharArchiveMock
            ->expects($this->once())
            ->method('execute')
            ->with(Environment::PHAR_ARCHIVE_NAME)
            ->willThrowException(new PharException());

        // Assert exception.
        $this->expectException(UnableToCreatePharArchive::class);
        $this->expectExceptionMessage(
            (new UnableToCreatePharArchive())->getMessage()
        );

        // Assertions.
        $this->rootDirectoryMock->expects($this->never())->method('packageDevSrcDirectory');

        // Starting test.
        $this->command->handle();
    }
}
