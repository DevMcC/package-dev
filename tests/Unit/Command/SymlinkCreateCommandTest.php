<?php

namespace DevMcC\PackageDev\Test\Unit\Command;

use DevMcC\PackageDev\Command\SymlinkCreateCommand;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SymlinkCreateCommandTest extends TestCase
{
    /** @var MockObject|Environment $environmentMock */
    private $environmentMock;
    /** @var MockObject|Output $outputMock */
    private $outputMock;

    /** @var SymlinkCreateCommand $command */
    private $command;

    protected function setUp(): void
    {
        /** @var MockObject|Environment */
        $this->environmentMock = $this->createMock(Environment::class);
        /** @var MockObject|Output */
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new SymlinkCreateCommand(
            $this->environmentMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('createSymlinks');

        // Starting test.
        $this->command->handle();
    }
}
