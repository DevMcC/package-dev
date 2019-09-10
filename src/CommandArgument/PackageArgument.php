<?php

namespace DevMcC\PackageDev\CommandArgument;

use DevMcC\PackageDev\Exception\InvalidPackageName;
use DevMcC\PackageDev\Exception\PackageArgumentWasNotSupplied;

class PackageArgument
{
    private const PATTERN_PACKAGE_NAME = '/[0-9a-zA-Z]+\/[0-9a-zA-Z]/';

    /**
     * @var string
     */
    private $package;

    public function __construct(ProcessArguments $processArguments)
    {
        if (!$processArguments->argument()) {
            throw new PackageArgumentWasNotSupplied;
        }

        if (!preg_match(self::PATTERN_PACKAGE_NAME, $processArguments->argument())) {
            throw new InvalidPackageName($processArguments->argument());
        }

        $this->package = $processArguments->argument();
    }

    public function package(): string
    {
        return $this->package;
    }
}
