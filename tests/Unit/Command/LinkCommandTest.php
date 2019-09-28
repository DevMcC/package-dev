<?php

namespace DevMcC\PackageDev\Test\Command;

use DevMcC\PackageDev\Command\LinkCommand;
use DevMcC\PackageDev\CommandArgument\PackageArgument;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LinkCommandTest extends TestCase
{
    /**
     * @var MockObject|PackageArgument $packageArgumentMock
     * @var MockObject|Environment $environmentMock
     * @var MockObject|Output $outputMock
     */
    private $packageArgumentMock;
    private $environmentMock;
    private $outputMock;

    /**
     * @var LinkCommand $command
     */
    private $command;

    protected function setUp(): void
    {
        $this->packageArgumentMock = $this->createMock(PackageArgument::class);
        $this->environmentMock = $this->createMock(Environment::class);
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new LinkCommand(
            $this->packageArgumentMock,
            $this->environmentMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        // Assertion.
        $this->packageArgumentMock
            ->expects($this->once())
            ->method('package')
            ->willReturn('test/pak');

        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('link')
            ->with('test/pak');

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('Package "test/pak" has been linked');

        // Starting test.
        $this->command->handle();
    }
}
