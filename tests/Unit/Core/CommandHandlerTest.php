<?php

namespace DevMcC\PackageDev\Test\Unit\Core;

use DevMcC\PackageDev\Command\Command;
use DevMcC\PackageDev\Command\HelpCommand;
use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Core\DependencyInjection;
use DevMcC\PackageDev\Core\CommandHandler;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Exception\Core\TerminateCommand;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommandHandlerTest extends TestCase
{
    /** @var MockObject|ProcessArguments $processArgumentsMock */
    private $processArgumentsMock;
    /** @var MockObject|CommandMapping $commandMappingMock */
    private $commandMappingMock;
    /** @var MockObject|DependencyInjection $dependencyInjectionMock */
    private $dependencyInjectionMock;
    /** @var MockObject|Output $outputMock */
    private $outputMock;

    /** @var CommandHandler $commandHandler */
    private $commandHandler;

    protected function setUp(): void
    {
        /** @var MockObject|ProcessArguments */
        $this->processArgumentsMock = $this->createMock(ProcessArguments::class);
        /** @var MockObject|CommandMapping */
        $this->commandMappingMock = $this->createMock(CommandMapping::class);
        /** @var MockObject|DependencyInjection */
        $this->dependencyInjectionMock = $this->createMock(DependencyInjection::class);
        /** @var MockObject|Output */
        $this->outputMock = $this->createMock(Output::class);

        $this->commandHandler = new CommandHandler(
            $this->processArgumentsMock,
            $this->commandMappingMock,
            $this->dependencyInjectionMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        $stubCommandName = 'test123';
        $stubCommandClassName = 'TestCommand';
        $commandMock = $this->createMock(Command::class);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('command')
            ->willReturn($stubCommandName);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with($stubCommandName)
            ->willReturn(true);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('getMapping')
            ->willReturn([$stubCommandName => $stubCommandClassName]);

        // Assertion.
        $this->dependencyInjectionMock
            ->expects($this->once())
            ->method('resolveClassName')
            ->with($stubCommandClassName)
            ->willReturn($commandMock);

        // Assertion.
        $commandMock->expects($this->once())->method('handle');

        // Assertions.
        $this->outputMock->expects($this->never())->method('line');
        $this->outputMock->expects($this->never())->method('list');

        // Starting test.
        $this->commandHandler->handle();
    }

    public function testHandleWhenCommandWasNotSupplied(): void
    {
        $stubCommandMapping = [
            HelpCommand::COMMAND_NAME => HelpCommand::class,
            InitCommand::COMMAND_NAME => InitCommand::class
        ];

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(true);

        // Assertion.
        $this->outputMock
            ->expects($this->exactly(6))
            ->method('line')
            ->withConsecutive(
                ['PackageDev'],
                [],
                ['Usage:'],
                ['package-dev [command] [arguments]', 1],
                [],
                ['Commands:']
            );

        // Assertion.
        $this->commandMappingMock
            ->expects($this->exactly(3))
            ->method('getMapping')
            ->willReturn($stubCommandMapping);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('list')
            ->with([
                [HelpCommand::COMMAND_NAME, HelpCommand::COMMAND_DESCRIPTION],
                [InitCommand::COMMAND_NAME, InitCommand::COMMAND_DESCRIPTION],
            ]);

        // Assertions.
        $this->processArgumentsMock->expects($this->never())->method('command');
        $this->commandMappingMock->expects($this->never())->method('commandExists');
        $this->dependencyInjectionMock->expects($this->never())->method('resolveClassName');

        // Starting test.
        $this->commandHandler->handle();
    }

    public function testHandleWhenCommandNotFoundThrowsTerminateCommand(): void
    {
        $stubCommandName = 'test123';

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('command')
            ->willReturn($stubCommandName);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with($stubCommandName)
            ->willReturn(false);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('ERROR: Command "test123" not found');

        // Assert exception.
        $this->expectException(TerminateCommand::class);

        // Assertions.
        $this->commandMappingMock->expects($this->never())->method('getMapping');
        $this->dependencyInjectionMock->expects($this->never())->method('resolveClassName');
        $this->outputMock->expects($this->never())->method('list');

        // Starting test.
        $this->commandHandler->handle();
    }

    public function testHandleWhenExceptionThrowsTerminateCommand(): void
    {
        $stubCommandName = 'test123';
        $stubCommandClassName = 'TestCommand';
        $stubException = new Exception('test good');
        $commandMock = $this->createMock(Command::class);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('command')
            ->willReturn($stubCommandName);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('commandExists')
            ->with($stubCommandName)
            ->willReturn(true);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('getMapping')
            ->willReturn([$stubCommandName => $stubCommandClassName]);

        // Assertion.
        $this->dependencyInjectionMock
            ->expects($this->once())
            ->method('resolveClassName')
            ->with($stubCommandClassName)
            ->willReturn($commandMock);

        // Assertion.
        $commandMock
            ->expects($this->once())
            ->method('handle')
            ->willThrowException($stubException);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('ERROR: ' . $stubException->getMessage());

        // Assert exception.
        $this->expectException(TerminateCommand::class);

        // Assertion.
        $this->outputMock->expects($this->never())->method('list');

        // Starting test.
        $this->commandHandler->handle();
    }
}
