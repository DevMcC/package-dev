<?php

namespace DevMcC\PackageDev\Test\Unit;

use PHPUnit\Framework\TestCase;

class FileSystemTestCase extends TestCase
{
    /** @var string[] $filePaths */
    protected $filePaths = [];
    /** @var string[] $linkPaths */
    protected $linkPaths = [];

    protected function tearDown(): void
    {
        $paths = array_merge($this->linkPaths, $this->filePaths);

        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                unlink($path);
            }
        }

        $this->filePaths = [];
        $this->linkPaths = [];
    }

    protected function basename(string $path): string
    {
        return pathinfo($path)['basename'];
    }

    protected function pathForFile(bool $createFile): string
    {
        $this->filePaths[] = $path = $this->randomPath('file');

        if ($createFile) {
            $result = @touch($path);

            if (!$result) {
                $this->fail('Failed to create temporary file.');
            }
        }

        return $path;
    }

    protected function pathForLink(?string $filePath): string
    {
        $this->linkPaths[] = $path = $this->randomPath('link');

        if ($filePath) {
            $result = @symlink($filePath, $path);

            if (!$result) {
                $this->fail('Failed to create temporary link.');
            }
        }

        return $path;
    }

    protected function randomPath(string $prefix): string
    {
        while (true) {
            $path = sprintf(
                '%s/%s_fstc_test_%s',
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
