<?php

namespace DevMcC\PackageDev\Test\Unit\Command;

use DevMcC\PackageDev\Command\UnlinkCommand;
use DevMcC\PackageDev\CommandArgument\PackageArgument;
use DevMcC\PackageDev\Core\Output;
use DevMcC\PackageDev\Environment\Environment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UnlinkCommandTest extends TestCase
{
    /** @var MockObject&PackageArgument $packageArgumentMock */
    private $packageArgumentMock;
    /** @var MockObject&Environment $environmentMock */
    private $environmentMock;
    /** @var MockObject&Output $outputMock */
    private $outputMock;

    /** @var UnlinkCommand $command */
    private $command;

    protected function setUp(): void
    {
        $this->packageArgumentMock = $this->createMock(PackageArgument::class);
        $this->environmentMock = $this->createMock(Environment::class);
        $this->outputMock = $this->createMock(Output::class);

        $this->command = new UnlinkCommand(
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
            ->method('unlink')
            ->with($stubPackage);

        // Assertion.
        $this->outputMock
            ->expects($this->once())
            ->method('line')
            ->with('Package ' . $stubPackage . ' has been unlinked');

        // Starting test.
        $this->command->handle();
    }
}
