<?php

namespace DevMcC\PackageDev\Test\Command;

use DevMcC\PackageDev\Command\SymlinkRemoveCommand;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SymlinkRemoveCommandTest extends TestCase
{
    /**
     * @var MockObject|Environment $environmentMock
     * @var MockObject|Output $outputMock
     */
    private $environmentMock;
    private $outputMock;

    /**
     * @var SymlinkRemoveCommand $command
     */
    private $command;

    protected function setUp(): void
    {
        $this->environmentMock = $this->createMock(Environment::class);
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new SymlinkRemoveCommand(
            $this->environmentMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('removeSymlinks');

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('All symlinks have been Removed');

        // Starting test.
        $this->command->handle();
    }
}
