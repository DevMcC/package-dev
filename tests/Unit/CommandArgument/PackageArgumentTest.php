<?php

namespace DevMcC\PackageDev\Test\CommandArgument;

use DevMcC\PackageDev\CommandArgument\PackageArgument;
use DevMcC\PackageDev\CommandArgument\ProcessArguments;
use DevMcC\PackageDev\Exception\InvalidPackageName;
use DevMcC\PackageDev\Exception\PackageArgumentWasNotSupplied;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PackageArgumentTest extends TestCase
{
    /**
     * @var MockObject&ProcessArguments $processArgumentsMock
     */
    private $processArgumentsMock;

    protected function setUp(): void
    {
        $this->processArgumentsMock = $this->createMock(ProcessArguments::class);
    }

    /**
     * @dataProvider validPackageNamesDataProvider
     */
    public function testConstruct(string $packageName): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argument')
            ->willReturn($packageName);

        // Starting test.
        $result = new PackageArgument(
            $this->processArgumentsMock
        );

        // Assertion.
        $this->assertSame($packageName, $result->package());
    }

    public function testConstructWhenPackageArgumentWasNotSupplied(): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(true);

        // Assertion.
        $this->processArgumentsMock->expects($this->never())->method('argument');

        // Assert exception.
        $this->expectException(PackageArgumentWasNotSupplied::class);
        $this->expectExceptionMessage('Package argument was not supplied');

        // Starting test.
        new PackageArgument(
            $this->processArgumentsMock
        );
    }

    /**
     * @dataProvider invalidPackageNamesDataProvider
     */
    public function testConstructWhenInvalidPackageName(string $packageName): void
    {
        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argumentWasNotSupplied')
            ->willReturn(false);

        // Assertion.
        $this->processArgumentsMock
            ->expects($this->once())
            ->method('argument')
            ->willReturn($packageName);

        // Assert exception.
        $this->expectException(InvalidPackageName::class);
        $this->expectExceptionMessage('Package name "' . $packageName . '" is invalid');

        // Starting test.
        new PackageArgument(
            $this->processArgumentsMock
        );
    }

    public function validPackageNamesDataProvider(): array
    {
        return [
            ['vendor/package'],
            ['vendor12/package12'],
            ['ven-dor/pack-age'],
            ['ven-dor12/pack-age12'],
            ['vendor/pack-pack-pack-pack-pack-package'],
        ];
    }

    public function invalidPackageNamesDataProvider(): array
    {
        return [
            ['vendor/'],
            ['/package'],
            ['vendor/package/no'],
            ['vEnDoR/pAcKaGe'],
        ];
    }
}
