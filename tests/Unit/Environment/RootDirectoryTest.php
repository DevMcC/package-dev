<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\RootDirectory;
use PHPUnit\Framework\TestCase;

class RootDirectoryTest extends TestCase
{
    public function testGetters(): void
    {
        $stubRootDirectory = 'testing';
        $stubPackageDevSrcDirectory = 'TEST';

        // Starting test.
        $result = new RootDirectory($stubRootDirectory, $stubPackageDevSrcDirectory);

        // Assertion.
        $this->assertSame($stubRootDirectory, $result->rootDirectory());
        $this->assertSame($stubPackageDevSrcDirectory, $result->packageDevSrcDirectory());
    }
}
