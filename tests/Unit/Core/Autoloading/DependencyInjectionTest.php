<?php

namespace DevMcC\PackageDev\Test\Core\Autoloading;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Core\Autoloading\DependencyInjection;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeA;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeAA;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeAAA;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeAAB;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeAB;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeABA;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeABB;
use DevMcC\PackageDev\Test\Stub\Core\Autoloading\DIStubTreeABBA;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DependencyInjectionTest extends TestCase
{
    /** @var MockObject|ProcessArguments $processArgumentsMock */
    private $processArgumentsMock;
    /** @var MockObject|RootDirectory $rootDirectoryMock */
    private $rootDirectoryMock;

    /** @var DependencyInjection $di */
    private $di;

    protected function setUp(): void
    {
        $this->processArgumentsMock = $this->createMock(ProcessArguments::class);
        $this->rootDirectoryMock = $this->createMock(RootDirectory::class);

        $this->di = new DependencyInjection(
            $this->processArgumentsMock,
            $this->rootDirectoryMock
        );
    }

    public function testResolveClassName(): void
    {
        // Starting test.

        /**
         * @var DIStubTreeA $result
         */
        $result = $this->di->resolveClassName(DIStubTreeA::class);

        // Assertion.
        $this->assertInstanceOf(DIStubTreeA::class, $result);
        $this->assertInstanceOf(DIStubTreeAA::class, $result->diStubTreeAA);
        $this->assertInstanceOf(DIStubTreeAAA::class, $result->diStubTreeAA->diStubTreeAAA);
        $this->assertInstanceOf(DIStubTreeAAB::class, $result->diStubTreeAA->diStubTreeAAB);
        $this->assertInstanceOf(DIStubTreeAB::class, $result->diStubTreeAB);
        $this->assertInstanceOf(DIStubTreeABA::class, $result->diStubTreeAB->diStubTreeABA);
        $this->assertInstanceOf(DIStubTreeABB::class, $result->diStubTreeAB->diStubTreeABB);
        $this->assertInstanceOf(DIStubTreeABBA::class, $result->diStubTreeAB->diStubTreeABB->diStubTreeABBA);
    }

    /**
     * @dataProvider builtInClassesDataProvider
     */
    public function testResolveClassNameWithBuiltInClasses(
        string $stubClassName,
        string $builtInMockName
    ): void {
        // Starting test.
        $result = $this->di->resolveClassName($stubClassName);

        // Assertion.
        $this->assertSame($this->$builtInMockName, $result);
    }

    // public function testResolveClassNameWillCacheResolvedClassNames

    public function builtInClassesDataProvider(): array
    {
        return [
            [ProcessArguments::class, 'processArgumentsMock'],
            [RootDirectory::class, 'rootDirectoryMock'],
        ];
    }
}
