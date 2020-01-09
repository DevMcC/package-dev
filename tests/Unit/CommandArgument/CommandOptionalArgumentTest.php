<?php

namespace DevMcC\PackageDev\Test\Unit\CommandArgument;

use DevMcC\PackageDev\CommandArgument\CommandOptionalArgument;
use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Exception\Command\CommandNotFound;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandOptionalArgumentTest extends TestCase
{
    /** @var MockObject&CommandMapping $commandMappingMock */
    private $commandMappingMock;
    /** @var MockObject&ProcessArguments $processArgumentsMock */
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
        $stubCommand = 'test';

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argument')
            ->willReturn($stubCommand);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with($stubCommand)
            ->willReturn(false);

        // Assert Exception.
        $this->expectException(CommandNotFound::class);
        $this->expectExceptionMessage(
            (new CommandNotFound($stubCommand))->getMessage()
        );

        // Starting test.
        new CommandOptionalArgument(
            $this->commandMappingMock,
            $this->processArgumentsMock
        );
    }
}
