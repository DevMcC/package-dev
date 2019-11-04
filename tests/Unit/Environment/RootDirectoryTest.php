<?php

namespace DevMcC\PackageDev\Test\Environment;

use DevMcC\PackageDev\Environment\RootDirectory;
use PHPUnit\Framework\TestCase;

class RootDirectoryTest extends TestCase
{
    public function testGetters(): void
    {
        $stubRootDirectory = 'testing';

        // Starting test.
        $result = new RootDirectory($stubRootDirectory);

        // Assertion.
        $this->assertSame($stubRootDirectory, $result->rootDirectory());
    }
}
