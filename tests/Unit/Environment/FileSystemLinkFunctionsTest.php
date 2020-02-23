<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Test\Unit\FileSystemTestCase;

/**
 * The link functions are seperately tested we are not able to test link functions through vfsStream.
 * Files are created in the tmp directory and will always try to clean up the mess.
 */
class FileSystemLinkFunctionsTest extends FileSystemTestCase
{
    /** @var RootDirectory $rootDirectoryStub */
    private $rootDirectoryStub;

    /** @var FileSystem $fileSystem */
    private $fileSystem;

    protected function setUp(): void
    {
        $this->rootDirectoryStub = new RootDirectory(sys_get_temp_dir(), sys_get_temp_dir());

        $this->fileSystem = new FileSystem(
            $this->rootDirectoryStub
        );
    }

    public function testDoesLinkExist(): void
    {
        // Preparing test.
        list('as' => $stubPathOfAs) = $this->prepareLink(true);

        // Starting test.
        $result = $this->fileSystem->doesLinkExist(
            $this->basename($stubPathOfAs)
        );

        // Assertion.
        $this->assertTrue($result);
    }

    public function testDoesLinkExistWithoutLink(): void
    {
        // Preparing test.
        list('as' => $stubPathOfAs) = $this->prepareLink(false);

        // Starting test.
        $result = $this->fileSystem->doesLinkExist(
            $this->basename($stubPathOfAs)
        );

        // Assertion.
        $this->assertFalse($result);
    }

    public function testLinkFileAs(): void
    {
        // Preparing test.
        list('file' => $stubPathOfFile, 'as' => $stubPathOfAs) = $this->prepareLink(false);

        // Starting test.
        $result = $this->fileSystem->linkFileAs(
            $this->basename($stubPathOfFile),
            $this->basename($stubPathOfAs)
        );

        // Assertion.
        $this->assertTrue($result);
        $this->assertTrue(is_link($stubPathOfAs));
    }

    public function testLinkFileAsWhenUnableToLinkFile(): void
    {
        // Preparing test.
        list('file' => $stubPathOfFile, 'as' => $stubPathOfAs) = $this->prepareLink(true);

        // Starting test.
        $result = $this->fileSystem->linkFileAs(
            $this->basename($stubPathOfFile),
            $this->basename($stubPathOfAs)
        );

        // Assertion.
        $this->assertFalse($result);
    }

    /**
     * Registers full paths into the properties, but returns relative paths.
     *
     * @return string[]
     */
    private function prepareLink(bool $createLink): array
    {
        $filePath = $this->pathForFile(true);
        $linkPath = $this->pathForLink($createLink ? $filePath : null);

        return ['file' => $filePath, 'as' => $linkPath];
    }

    protected function randomPath(string $prefix): string
    {
        return parent::randomPath($prefix . '__fslft');
    }
}
