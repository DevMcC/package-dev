<?php

namespace DevMcC\PackageDev\CommandArgument;

use DevMcC\PackageDev\Exception\InvalidPackageName;
use DevMcC\PackageDev\Exception\PackageArgumentWasNotSupplied;

class PackageArgument
{
    private const PATTERN_PACKAGE_NAME = '/^[0-9a-z-]+\/[0-9a-z-]+$/';

    /**
     * @var string
     */
    private $package;

    /**
     * @throws PackageArgumentWasNotSupplied
     * @throws InvalidPackageName
     */
    public function __construct(ProcessArguments $processArguments)
    {
        if ($processArguments->argumentWasNotSupplied()) {
            throw new PackageArgumentWasNotSupplied;
        }

        $packageArgument = $processArguments->argument();

        if (!preg_match(self::PATTERN_PACKAGE_NAME, $packageArgument)) {
            throw new InvalidPackageName($packageArgument);
        }

        $this->package = $packageArgument;
    }

    public function package(): string
    {
        return $this->package;
    }
}
