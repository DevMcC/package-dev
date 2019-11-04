<?php

namespace DevMcC\PackageDev\Test\Config;

use DevMcC\PackageDev\Command\HelpCommand;
use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Command\LinkCommand;
use DevMcC\PackageDev\Command\PharCommand;
use DevMcC\PackageDev\Command\SymlinkCreateCommand;
use DevMcC\PackageDev\Command\SymlinkRemoveCommand;
use DevMcC\PackageDev\Command\UnlinkCommand;
use DevMcC\PackageDev\Config\CommandMapping;
use PHPUnit\Framework\TestCase;

class CommandMappingTest extends TestCase
{
    /** @var CommandMapping $commandMapping */
    private $commandMapping;

    protected function setUp(): void
    {
        $this->commandMapping = new CommandMapping;
    }

    public function testGetMapping(): void
    {
        $commands = [];

        foreach ($this->commands() as $command) {
            $commands[$command::COMMAND_NAME] = $command;
        }

        // Starting test.
        $result = $this->commandMapping->getMapping();

        // Assertion.
        $this->assertCount(count($commands), $result);
        $this->assertSame($commands, $result);
    }

    /**
     * @dataProvider existingCommandsDataProvider
     */
    public function testCommandExists(string $command): void
    {
        // Starting test.
        $result = $this->commandMapping->commandExists($command::COMMAND_NAME);

        // Assertion.
        $this->assertTrue($result);
    }

    public function testCommandExistsWhenFalse(): void
    {
        // Starting test.
        $result = $this->commandMapping->commandExists('this does not exist, right?');

        // Assertion.
        $this->assertFalse($result);
    }

    public function existingCommandsDataProvider(): array
    {
        return array_map(
            function (string $command) {
                return [$command];
            },
            $this->commands()
        );
    }

    /**
     * @return string[]
     */
    private function commands(): array
    {
        return [
            HelpCommand::class,
            InitCommand::class,
            LinkCommand::class,
            UnlinkCommand::class,
            SymlinkCreateCommand::class,
            SymlinkRemoveCommand::class,
            PharCommand::class,
        ];
    }
}
