<?php

namespace DevMcC\PackageDev\Test\Command;

use DevMcC\PackageDev\CommandArgument\CommandOptionalArgument;
use DevMcC\PackageDev\Command\HelpCommand;
use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Config\CommandMapping;
use DevMcC\PackageDev\Core\Output;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HelpCommandTest extends TestCase
{
    /** @var MockObject&CommandOptionalArgument $commandOptionalArgumentMock */
    private $commandOptionalArgumentMock;
    /** @var MockObject&CommandMapping $commandMappingMock */
    private $commandMappingMock;
    /** @var MockObject&Output $outputMock */
    private $outputMock;

    /** @var HelpCommand $command */
    private $command;

    protected function setUp(): void
    {
        $this->commandOptionalArgumentMock = $this->createMock(CommandOptionalArgument::class);
        $this->commandMappingMock = $this->createMock(CommandMapping::class);
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new HelpCommand(
            $this->commandOptionalArgumentMock,
            $this->commandMappingMock,
            $this->outputMock
        );
    }

    public function testHandleProvidesHelpForGivenCommand(): void
    {
        // Assertion.
        $this->commandOptionalArgumentMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('getMapping')
            ->willReturn([
                InitCommand::COMMAND_NAME => InitCommand::class,
                HelpCommand::COMMAND_NAME => HelpCommand::class,
            ]);

        // Assertion.
        $this->commandOptionalArgumentMock
            ->expects($this->once())
            ->method('command')
            ->willReturn(InitCommand::COMMAND_NAME);

        // Assertion.
        $this->outputMock
            ->expects($this->exactly(5))
            ->method('line')
            ->withConsecutive(
                ['Usage:'],
                [InitCommand::COMMAND_USAGE, 1],
                [],
                ['Description:'],
                [InitCommand::COMMAND_DESCRIPTION, 1]
            );

        // Starting test.
        $this->command->handle();
    }

    public function testHandleProvidesHelpForGivenHelpCommandWithRecursion(): void
    {
        // Assertion.
        $this->commandOptionalArgumentMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->commandMappingMock
            ->expects($this->once())
            ->method('getMapping')
            ->willReturn([
                InitCommand::COMMAND_NAME => InitCommand::class,
                HelpCommand::COMMAND_NAME => HelpCommand::class,
            ]);

        // Assertion.
        $this->commandOptionalArgumentMock
            ->expects($this->once())
            ->method('command')
            ->willReturn(HelpCommand::COMMAND_NAME);

        // Assertion.
        $this->outputMock
            ->expects($this->exactly(9))
            ->method('line')
            ->withConsecutive(
                ['<recursion>'],
                [],
                ['Usage:'],
                [HelpCommand::COMMAND_USAGE, 1],
                [],
                ['Description:'],
                [HelpCommand::COMMAND_DESCRIPTION, 1],
                [],
                ['</recursion>']
            );

        // Starting test.
        $this->command->handle();
    }

    public function testHandleProvidesHelpForHelpCommandWhenNoneWasGiven(): void
    {
        // Assertion.
        $this->commandOptionalArgumentMock
            ->expects($this->once())
            ->method('commandWasNotSupplied')
            ->willReturn(true);

        // Assertions.
        $this->commandMappingMock->expects($this->never())->method('getMapping');
        $this->commandOptionalArgumentMock->expects($this->never())->method('command');

        // Assertion.
        $this->outputMock
            ->expects($this->exactly(5))
            ->method('line')
            ->withConsecutive(
                ['Usage:'],
                [HelpCommand::COMMAND_USAGE, 1],
                [],
                ['Description:'],
                [HelpCommand::COMMAND_DESCRIPTION, 1]
            );

        // Starting test.
        $this->command->handle();
    }
}
