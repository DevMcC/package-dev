<?php

namespace DevMcC\PackageDev\Test\Unit\CommandArgument;

use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use PHPUnit\Framework\TestCase;

class ProcessArgumentsTest extends TestCase
{
    /**
     * @dataProvider validProcessArgumentsDataProvider
     *
     * @param string[] $stubProcessArguments
     */
    public function testConstruct(array $stubProcessArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($stubProcessArguments);

        // Assertions.
        $this->assertFalse($result->commandWasNotSupplied());
        $this->assertFalse($result->argumentWasNotSupplied());
        $this->assertSame(trim($stubProcessArguments[1]), $result->command());
        $this->assertSame(trim($stubProcessArguments[2]), $result->argument());
    }

    /**
     * @dataProvider invalidCommandDataProvider
     *
     * @param string[]|null[] $stubProcessArguments
     */
    public function testConstructWhenCommandWasNotSupplied(array $stubProcessArguments): void
    {
        $expectedArgument = trim((string) $stubProcessArguments[2]);

        // Starting test.
        $result = new ProcessArguments($stubProcessArguments);

        // Assertions.
        $this->assertTrue($result->commandWasNotSupplied());
        $this->assertFalse($result->argumentWasNotSupplied());
        $this->assertNull($result->command());
        $this->assertSame($expectedArgument, $result->argument());
    }

    /**
     * @dataProvider invalidArgumentDataProvider
     *
     * @param string[]|null[] $stubProcessArguments
     */
    public function testConstructWhenArgumentWasNotSupplied(array $stubProcessArguments): void
    {
        $expectedCommand = trim((string) $stubProcessArguments[1]);

        // Starting test.
        $result = new ProcessArguments($stubProcessArguments);

        // Assertions.
        $this->assertFalse($result->commandWasNotSupplied());
        $this->assertTrue($result->argumentWasNotSupplied());
        $this->assertSame($expectedCommand, $result->command());
        $this->assertNull($result->argument());
    }

    /**
     * @dataProvider invalidProcessArgumentsDataProvider
     *
     * @param string[] $stubProcessArguments
     */
    public function testConstructWhenBothCommandAndArgumentAreNotSupplied(array $stubProcessArguments): void
    {
        // Starting test.
        $result = new ProcessArguments($stubProcessArguments);

        // Assertions.
        $this->assertTrue($result->commandWasNotSupplied());
        $this->assertTrue($result->argumentWasNotSupplied());
        $this->assertNull($result->command());
        $this->assertNull($result->argument());
    }

    /**
     * @return array[]
     */
    public function validProcessArgumentsDataProvider(): array
    {
        return [
            [['package-dev', 'command', 'argument']],
            [['package-dev', '   command   ', '   argument   ']],
            [['package-dev', '   0   ', '   0   ']],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidCommandDataProvider(): array
    {
        return [
            [['package-dev', '', 'argument']],
            [['package-dev', '   ', 'argument']],
            [['package-dev', null, 'argument']],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidArgumentDataProvider(): array
    {
        return [
            [['package-dev', 'command', '']],
            [['package-dev', 'command', '   ']],
            [['package-dev', 'command', null]],
            [['package-dev', 'command']],
        ];
    }

    /**
     * @return array[]
     */
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
