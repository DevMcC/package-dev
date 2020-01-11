<?php

namespace DevMcC\PackageDev\Test\Unit\Environment;

use DevMcC\PackageDev\Environment\RootDirectory;
use PHPUnit\Framework\TestCase;

class RootDirectoryTest extends TestCase
{
    public function testGetters(): void
    {
        $stubRootDirectory = 'testing';
        $stubPackageDevRootDirectory = 'TEST';

        // Starting test.
        $result = new RootDirectory($stubRootDirectory, $stubPackageDevRootDirectory);

        // Assertion.
        $this->assertSame($stubRootDirectory, $result->rootDirectory());
        $this->assertSame($stubPackageDevRootDirectory, $result->packageDevRootDirectory());
    }
}
