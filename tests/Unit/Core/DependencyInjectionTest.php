<?php

namespace DevMcC\PackageDev\Test\Unit\Core;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Core\DependencyInjection;
use DevMcC\PackageDev\Environment\RootDirectory;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeA;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeAA;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeAAA;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeAAB;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeAB;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeABA;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeABB;
use DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest\DIStubTreeABBA;
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
        string $stubBuiltInMockName
    ): void {
        // Starting test.
        $result = $this->di->resolveClassName($stubClassName);

        // Assertion.
        $this->assertSame($this->$stubBuiltInMockName, $result);
    }

    public function testResolveClassNameWillCacheResolvedClassNames(): void
    {
        // Starting test.
        $firstResolve = $this->di->resolveClassName(DIStubTreeA::class);
        $secondResolve = $this->di->resolveClassName(DIStubTreeA::class);

        // Assertion.
        $this->assertTrue($firstResolve === $secondResolve, 'Failed asserting that the same object was resolved.');
    }

    public function builtInClassesDataProvider(): array
    {
        return [
            [ProcessArguments::class, 'processArgumentsMock'],
            [RootDirectory::class, 'rootDirectoryMock'],
        ];
    }
}
