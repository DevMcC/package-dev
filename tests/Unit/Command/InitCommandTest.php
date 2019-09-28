<?php

namespace DevMcC\PackageDev\Test\Command;

use DevMcC\PackageDev\Command\InitCommand;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InitCommandTest extends TestCase
{
    /**
     * @var MockObject|Environment $environmentMock
     * @var MockObject|Output $outputMock
     */
    private $environmentMock;
    private $outputMock;

    /**
     * @var InitCommand $command
     */
    private $command;

    protected function setUp(): void
    {
        $this->environmentMock = $this->createMock(Environment::class);
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new InitCommand(
            $this->environmentMock,
            $this->outputMock
        );
    }

    public function testHandleWhenInitializeReturnsTrue(): void
    {
        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('initialize')
            ->willReturn(true);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('Environment has been initialized');

        // Starting test.
        $this->command->handle();
    }

    public function testHandleWhenInitializeReturnsFalse(): void
    {
        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('initialize')
            ->willReturn(false);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('Environment was already initialized');

        // Starting test.
        $this->command->handle();
    }
}
