<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\FileSystem;
use DevMcC\PackageDev\Environment\RootDirectory;
use PHPUnit\Framework\TestCase;

/**
 * The link functions are seperately tested we are not able to test link functions through vfsStream.
 * Files are created in the tmp directory and will always try to clean up the mess.
 */
class FileSystemLinkFunctionsTest extends TestCase
{
    /** @var RootDirectory $rootDirectoryStub */
    private $rootDirectoryStub;

    /** @var FileSystem $fileSystem */
    private $fileSystem;

    /** @var string $pathOfFileStub */
    private $pathOfFileStub;
    /** @var string $pathOfAsStub */
    private $pathOfAsStub;

    protected function setUp(): void
    {
        $this->rootDirectoryStub = new RootDirectory(sys_get_temp_dir());

        $this->fileSystem = new FileSystem(
            $this->rootDirectoryStub
        );
    }

    protected function tearDown(): void
    {
        $paths = [$this->pathOfAsStub ?? '', $this->pathOfFileStub ?? ''];

        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                unlink($path);
            }
        }

        unset($this->pathOfAsStub);
        unset($this->pathOfFileStub);
    }

    public function testDoesLinkExist(): void
    {
        // Preparing test.
        list('as' => $stubPathOfAs) = $this->prepareLink(true);

        // Starting test.
        $result = $this->fileSystem->doesLinkExist($stubPathOfAs);

        // Assertion.
        $this->assertTrue($result);
    }

    public function testDoesLinkExistWithoutLink(): void
    {
        // Preparing test.
        list('as' => $stubPathOfAs) = $this->prepareLink(false);

        // Starting test.
        $result = $this->fileSystem->doesLinkExist($stubPathOfAs);

        // Assertion.
        $this->assertFalse($result);
    }

    public function testLinkFileAs(): void
    {
        // Preparing test.
        list('file' => $stubPathOfFile, 'as' => $stubPathOfAs) = $this->prepareLink(false);

        // Starting test.
        $result = $this->fileSystem->linkFileAs($stubPathOfFile, $stubPathOfAs);

        // Assertion.
        $this->assertTrue($result);
        $this->assertTrue(is_link($this->pathOfAsStub));
    }

    public function testLinkFileAsWhenUnableToLinkFile(): void
    {
        // Preparing test.
        list('file' => $stubPathOfFile, 'as' => $stubPathOfAs) = $this->prepareLink(true);

        // Starting test.
        $result = $this->fileSystem->linkFileAs($stubPathOfFile, $stubPathOfAs);

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
        $filePath = $this->pathOfFileStub = $this->randomPath('file');

        $result = @touch($filePath);

        if (!$result) {
            $this->fail('Failed to create temporary file.');
        }

        $linkPath = $this->pathOfAsStub = $this->randomPath('link');

        if ($createLink) {
            $result = symlink($filePath, $linkPath);

            if (!$result) {
                $this->fail('Failed to create temporary link.');
            }
        }

        return [
            'file' => pathinfo($filePath)['filename'],
            'as' => pathinfo($linkPath)['filename'],
        ];
    }

    private function randomPath(string $prefix): string
    {
        while (true) {
            $path = sprintf(
                '%s/%s__fsl_test_%s',
                sys_get_temp_dir(),
                $prefix,
                md5((string)rand())
            );

            if (file_exists($path)) {
                continue;
            }

            return $path;
        }
    }
}
