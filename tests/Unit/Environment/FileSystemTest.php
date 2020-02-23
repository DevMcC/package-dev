<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;

class FileSystemTest extends TestCase
{
    /** @var FileSystem $fileSystem */
    private $fileSystem;

    /** @var vfsStreamDirectory $vfs */
    private $vfs;
    /** @var string[] $randomPaths */
    private $randomPaths;

    protected function setUp(): void
    {
        $this->randomPaths = [];
        $this->vfs = vfsStream::setup();

        $this->fileSystem = new FileSystem(
            new RootDirectory($this->vfs->url(), $this->vfs->url())
        );
    }

    public function testDoesFileExist(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true);

        // Starting test.
        $result = $this->fileSystem->doesFileExist($stubPath);

        // Assertion.
        $this->assertTrue($result);
    }

    public function testDoesFileExistWithoutFile(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(false);

        // Starting test.
        $result = $this->fileSystem->doesFileExist($stubPath);

        // Assertion.
        $this->assertFalse($result);
    }

    public function testDoesDirectoryExist(): void
    {
        // Preparing test.
        $stubPath = $this->pathForDirectory(true);

        // Starting test.
        $result = $this->fileSystem->doesDirectoryExist($stubPath);

        // Assertion.
        $this->assertTrue($result);
    }

    public function testDoesDirectoryExistWithoutDirectory(): void
    {
        // Preparing test.
        $stubPath = $this->pathForDirectory(false);

        // Starting test.
        $result = $this->fileSystem->doesDirectoryExist($stubPath);

        // Assertion.
        $this->assertFalse($result);
    }

    public function testCreateFile(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(false);

        // Starting test.
        $result = $this->fileSystem->createFile($stubPath);

        // Assertion.
        $this->assertTrue($result);
        $this->assertTrue($this->vfs->hasChild($stubPath));
        $this->assertInstanceOf(vfsStreamFile::class, $this->vfs->getChild($stubPath));
    }

    public function testCreateFileFails(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(false);
        $this->vfs->chmod(0000);

        // Starting test.
        $result = $this->fileSystem->createFile($stubPath);

        // Assertion.
        $this->assertFalse($result);
        $this->assertFalse($this->vfs->hasChild($stubPath));
    }

    public function testCreateDirectory(): void
    {
        // Preparing test.
        $stubPath = $this->pathForDirectory(false);

        // Starting test.
        $result = $this->fileSystem->createDirectory($stubPath);

        // Assertion.
        $this->assertTrue($result);
        $this->assertTrue($this->vfs->hasChild($stubPath));
        $this->assertInstanceOf(vfsStreamDirectory::class, $this->vfs->getChild($stubPath));
    }

    public function testCreateDirectoryFails(): void
    {
        // Preparing test.
        $stubPath = $this->pathForDirectory(false);
        $this->vfs->chmod(0000);

        // Starting test.
        $result = $this->fileSystem->createDirectory($stubPath);

        // Assertion.
        $this->assertFalse($result);
        $this->assertFalse($this->vfs->hasChild($stubPath));
    }

    /**
     * @dataProvider fileContentDataProvider
     */
    public function testReadFromFile(string $stubContent): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true, $stubContent);

        // Starting test.
        $result = $this->fileSystem->readFromFile($stubPath);

        // Assertion.
        $this->assertSame($stubContent, $result);
    }

    public function testReadFromFileFails(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(false);

        // Starting test.
        $result = $this->fileSystem->readFromFile($stubPath);

        // Assertion.
        $this->assertNull($result);
    }

    public function testWriteToFile(): void
    {
        $stubOriginalContent = 'testing';
        $stubNewContent = 'this is working';

        // Preparing test.
        $stubPath = $this->pathForFile(true, $stubOriginalContent);

        /**
         * @var vfsStreamFile $stubFile
         */
        $stubFile = $this->vfs->getChild($stubPath);

        // Starting test.
        $result = $this->fileSystem->writeToFile($stubPath, $stubNewContent);

        // Assertion.
        $this->assertTrue($result);
        $this->assertSame($stubNewContent, $stubFile->getContent());
    }

    public function testWriteToFileFails(): void
    {
        $stubOriginalContent = 'testing';
        $stubNewContent = 'this is working';

        // Preparing test.
        $stubPath = $this->pathForFile(true, $stubOriginalContent);

        /** @var vfsStreamFile $stubFile */
        $stubFile = $this->vfs->getChild($stubPath);
        $stubFile->chmod(0000);

        // Starting test.
        $result = $this->fileSystem->writeToFile($stubPath, $stubNewContent);

        // Assertion.
        $this->assertFalse($result);
        $this->assertSame($stubOriginalContent, $stubFile->getContent());
    }

    public function testDeleteFile(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true);

        // Starting test.
        $result = $this->fileSystem->deleteFile($stubPath);

        // Assertion.
        $this->assertTrue($result);
        $this->assertFalse($this->vfs->hasChild($stubPath));
    }

    public function testDeleteFileFails(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true);
        $this->vfs->chmod(0000);

        // Starting test.
        $result = $this->fileSystem->deleteFile($stubPath);

        // Assertion.
        $this->assertFalse($result);
        $this->assertTrue($this->vfs->hasChild($stubPath));
    }

    public function testMoveFileTo(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true);
        $stubNewPath = $this->pathForFile(false);

        // Starting test.
        $result = $this->fileSystem->moveFileTo($stubPath, $stubNewPath);

        // Assertion.
        $this->assertTrue($result);
        $this->assertFalse($this->vfs->hasChild($stubPath));
        $this->assertTrue($this->vfs->hasChild($stubNewPath));
    }

    public function testMoveFileToFails(): void
    {
        // Preparing test.
        $stubPath = $this->pathForFile(true);
        $stubNewPath = $this->pathForFile(false);
        $this->vfs->chmod(0000);

        // Starting test.
        $result = $this->fileSystem->moveFileTo($stubPath, $stubNewPath);

        // Assertion.
        $this->assertFalse($result);
        $this->assertTrue($this->vfs->hasChild($stubPath));
        $this->assertFalse($this->vfs->hasChild($stubNewPath));
    }

    private function pathForFile(bool $createFile, ?string $content = ''): string
    {
        $path = $this->randomPath();

        if ($createFile) {
            $this->vfs->addChild(
                vfsStream::newFile($path)->withContent($content)
            );
        }

        return $path;
    }

    private function pathForDirectory(bool $createDirectory): string
    {
        $path = $this->randomPath();

        if ($createDirectory) {
            $this->vfs->addChild(
                vfsStream::newDirectory($path)
            );
        }

        return $path;
    }

    private function randomPath(): string
    {
        while (true) {
            $path = sprintf('FileSystemTest%s', md5((string)rand()));

            if (in_array($path, $this->randomPaths)) {
                continue;
            }

            $this->randomPaths[] = $path;

            return $path;
        }
    }

    /**
     * @return array[]
     */
    public function fileContentDataProvider(): array
    {
        return [
            ['testing'],
            [''],
        ];
    }
}
