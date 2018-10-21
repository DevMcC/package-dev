<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait AcceptsPackageArgument
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait AcceptsPackageArgument
{
    /**
     * The package string that has been sent with the command as an argument.
     *
     * @var string $packageArgument
     */
    protected $packageArgument;

    /**
     * Defines if the argument is optional.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return bool
     */
    protected function optionalPackageArgument()
    {
        return false;
    }

    /**
     * Sets the packageArgument.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    protected function setPackage()
    {
        $this->packageArgument = $this->argument('package', $this->optionalPackageArgument());
    }

    /**
     * Validates the packageArgument.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    protected function validatePackageArgument()
    {
        //Regex: matches a-z0-9-]/a-z0-9-
        if (!preg_match('/^([a-z0-9-]+)\/([a-z0-9-]+)$/i', $this->packageArgument)) {
            Output::abort('ERROR: Invalid package name.');
        }

        if (!is_dir($this->packagesPath.$this->packageArgument)) {
            Output::abort('ERROR: Package not found.');
        }

        if (!is_dir($this->vendorPath.$this->packageArgument)) {
            Output::abort('ERROR: Package was not found in vendor.');
        }
    }
}
