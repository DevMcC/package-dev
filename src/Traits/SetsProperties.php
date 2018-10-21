<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait SetsProperties
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait SetsProperties
{
    /**
     * The path to vendor, based on appBase.
     *
     * @var string $vendorPath
     */
    protected $vendorPath;

    /**
     * The path to packages, based on appBase.
     *
     * @var string $packagesPath
     */
    protected $packagesPath;

    /**
     * The path to package-dev.json.
     *
     * @var string $linkedPath
     */
    protected $linkedPath;

    /**
     * Sets all path properties from this trait.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    protected function setProperties()
    {
        $this->vendorPath = $this->appBase.'vendor/';
        $this->packagesPath = $this->appBase.'packages/';
        $this->linkedPath = $this->packagesPath.'package-dev.json';
    }
}
