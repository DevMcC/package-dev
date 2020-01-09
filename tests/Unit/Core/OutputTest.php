<?php

namespace DevMcC\PackageDev\Test\Unit\Core;

use DevMcC\PackageDev\Core\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    /** @var Output $output */
    private $output;

    protected function setUp(): void
    {
        $this->output = new Output;
    }

    /**
     * @dataProvider linesDataProvider
     */
    public function testLine(
        string $message,
        ?int $indent,
        string $expectedOutput
    ): void {
        // Assert echo.
        $this->expectOutputString($expectedOutput);

        // Starting test.
        $this->output->line($message, $indent);
    }

    public function testList(): void
    {
        $expectedOutput = <<<STRING
  testing                        wat
  tested                         123123
  te                             sting
  testingtesting123123           456456
  aaaaaaaaaaaaaaaaaaaaaaaaaaaaa  bc
  testUHH                        UHHH

STRING;

        $stubList = [
            ['testing', 'wat'],
            ['tested', '123123'],
            ['te', 'sting'],
            ['testingtesting123123', '456456'],
            ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'bc'],
            ['testUHH', 'UHHH'],
        ];

        // Assert echo.
        $this->expectOutputString($expectedOutput);

        // Starting test.
        $this->output->list($stubList);
    }

    public function linesDataProvider(): array
    {
        return [
            ['test', null, 'test' . PHP_EOL],
            ['test', 0, 'test' . PHP_EOL],
            ['test', 1, '  test' . PHP_EOL],
            ['test', 2, '    test' . PHP_EOL],
            ['test', 3, '      test' . PHP_EOL],
        ];
    }
}
