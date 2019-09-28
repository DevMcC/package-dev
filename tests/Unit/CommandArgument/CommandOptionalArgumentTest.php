<?php

namespace DevMcC\PackageDev\Test\CommandArgument;

use DevMcC\PackageDev\CommandArgument\CommandOptionalArgument;
use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Exception\CommandNotFound;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandOptionalArgumentTest extends TestCase
{
    /**
     * @var MockObject|CommandMapping $commandMappingMock
     * @var MockObject|ProcessArguments $processArgumentsMock
     */
    private $commandMappingMock;
    private $processArgumentsMock;

    protected function setUp(): void
    {
        $this->commandMappingMock = $this->createMock(CommandMapping::class);
        $this->processArgumentsMock = $this->createMock(ProcessArguments::class);
    }

    public function testConstruct(): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argument')
            ->willReturn('test');

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with('test')
            ->willReturn(true);

        // Starting test.
        $result = new CommandOptionalArgument(
            $this->commandMappingMock,
            $this->processArgumentsMock
        );

        // Assertions.
        $this->assertSame(false, $result->commandWasNotSupplied());
        $this->assertSame('test', $result->command());
    }

    public function testConstructWhenArgumentWasNotSupplied(): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(true);

        // Assertions.
        $this->processArgumentsMock->expects($this->never())->method('argument');
        $this->commandMappingMock->expects($this->never())->method('commandExists');

        // Starting test.
        $result = new CommandOptionalArgument(
            $this->commandMappingMock,
            $this->processArgumentsMock
        );

        // Assertions.
        $this->assertSame(true, $result->commandWasNotSupplied());
        $this->assertNull($result->command());
    }

    public function testConstructWhenCommandNotFound(): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argument')
            ->willReturn('test');

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with('test')
            ->willReturn(false);

        // Assert Exception.
        $this->expectException(CommandNotFound::class);
        $this->expectExceptionMessage('Command "test" not found');

        // Starting test.
        new CommandOptionalArgument(
            $this->commandMappingMock,
            $this->processArgumentsMock
        );
    }
}