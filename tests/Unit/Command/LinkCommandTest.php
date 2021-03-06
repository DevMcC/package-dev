<?php

namespace DevMcC\PackageDev\Test\Unit\Command;

use DevMcC\PackageDev\Command\LinkCommand;
use DevMcC\PackageDev\CommandArgument\PackageArgument;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LinkCommandTest extends TestCase
{
    /** @var MockObject|PackageArgument $packageArgumentMock */
    private $packageArgumentMock;
    /** @var MockObject|Environment $environmentMock */
    private $environmentMock;
    /** @var MockObject|Output $outputMock */
    private $outputMock;

    /** @var LinkCommand $command */
    private $command;

    protected function setUp(): void
    {
        /** @var MockObject|PackageArgument */
        $this->packageArgumentMock = $this->createMock(PackageArgument::class);
        /** @var MockObject|Environment */
        $this->environmentMock = $this->createMock(Environment::class);
        /** @var MockObject|Output */
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new LinkCommand(
            $this->packageArgumentMock,
            $this->environmentMock,
            $this->outputMock
        );
    }

    public function testHandle(): void
    {
        $stubPackage = 'test/pak';

        // Assertion.
        $this->packageArgumentMock
            ->expects($this->once())
            ->method('package')
            ->willReturn($stubPackage);

        // Assertion.
        $this->environmentMock
            ->expects($this->once())
            ->method('link')
            ->with($stubPackage);

        // Starting test.
        $this->command->handle();
    }
}
