<?php

namespace DevMcC\PackageDev\Test\Unit\Command;

use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InitCommandTest extends TestCase
{
    /** @var MockObject|Environment $environmentMock */
    private $environmentMock;
    /** @var MockObject|Output $outputMock */
    private $outputMock;

    /** @var InitCommand $command */
    private $command;

    protected function setUp(): void
    {
        /** @var MockObject|Environment */
        $this->environmentMock = $this->createMock(Environment::class);
        /** @var MockObject|Output */
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new InitCommand(
            $this->environmentMock,
            $this->outputMock
        );
    }

    /**
     * @dataProvider initializeEnvironmentDataProvider
     */
    public function testHandle(bool $stubHasBeenInitialized): void
    {
        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('initialize')
            ->willReturn($stubHasBeenInitialized);

        // Starting test.
        $this->command->handle();
    }

    /**
     * @return array[]
     */
    public function initializeEnvironmentDataProvider(): array
    {
        return [
            [true],
            [false],
        ];
    }
}
