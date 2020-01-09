<?php

namespace DevMcC\PackageDev\Test\Unit\CommandArgument;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use PHPUnit\Framework\TestCase;

class ProcessArgumentsTest extends TestCase
{
    /**
     * @dataProvider validProcessArgumentsDataProvider
     */
    public function testConstruct(array $processArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($processArguments);

        // Assertions.
        $this->assertFalse($result->commandWasNotSupplied());
        $this->assertFalse($result->argumentWasNotSupplied());
        $this->assertSame(trim($processArguments[1]), $result->command());
        $this->assertSame(trim($processArguments[2]), $result->argument());
    }

    /**
     * @dataProvider invalidCommandDataProvider
     */
    public function testConstructWhenCommandWasNotSupplied(array $processArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($processArguments);

        // Assertions.
        $this->assertTrue($result->commandWasNotSupplied());
        $this->assertFalse($result->argumentWasNotSupplied());
        $this->assertNull($result->command());
        $this->assertSame(trim($processArguments[2]), $result->argument());
    }

    /**
     * @dataProvider invalidArgumentDataProvider
     */
    public function testConstructWhenArgumentWasNotSupplied(array $processArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($processArguments);

        // Assertions.
        $this->assertFalse($result->commandWasNotSupplied());
        $this->assertTrue($result->argumentWasNotSupplied());
        $this->assertSame(trim($processArguments[1]), $result->command());
        $this->assertNull($result->argument());
    }

    /**
     * @dataProvider invalidProcessArgumentsDataProvider
     */
    public function testConstructWhenBothCommandAndArgumentAreNotSupplied(array $processArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($processArguments);

        // Assertions.
        $this->assertTrue($result->commandWasNotSupplied());
        $this->assertTrue($result->argumentWasNotSupplied());
        $this->assertNull($result->command());
        $this->assertNull($result->argument());
    }

    public function validProcessArgumentsDataProvider(): array
    {
        return [
            [['package-dev', 'command', 'argument']],
            [['package-dev', '   command   ', '   argument   ']],
            [['package-dev', '   0   ', '   0   ']],
        ];
    }

    public function invalidCommandDataProvider(): array
    {
        return [
            [['package-dev', '', 'argument']],
            [['package-dev', '   ', 'argument']],
            [['package-dev', null, 'argument']],
        ];
    }

    public function invalidArgumentDataProvider(): array
    {
        return [
            [['package-dev', 'command', '']],
            [['package-dev', 'command', '   ']],
            [['package-dev', 'command', null]],
            [['package-dev', 'command']],
        ];
    }

    public function invalidProcessArgumentsDataProvider(): array
    {
        return [
            [['package-dev', '', '']],
            [['package-dev', '   ', '   ']],
            [['package-dev', null, null]],
            [['package-dev']],
        ];
    }
}
