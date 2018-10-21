<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait ReadsLinkedFile
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait ReadsLinkedFile
{
    /**
     * An array of all linked packages.
     *
     * @var array $linkedPackages
     */
    protected $linkedPackages;

    /**
     * Reads package-dev.json and adds the decoded array into the linkedPackages property.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    protected function readLinkedFile()
    {
        $linkedContentsRaw = file_get_contents($this->linkedPath);

        if (!$linkedContentsRaw) {
            Output::abort('ERROR: Could not read package-dev.json.');
        }

        $this->linkedPackages = json_decode($linkedContentsRaw, true);

        if (!$this->linkedPackages) {
            Output::abort('ERROR: package-dev.json contains invalid json.');
        }

        if (!isset($this->linkedPackages['packages'])) {
            Output::abort('ERROR: No packages entry found.');
        }

        $this->linkedPackages = $this->linkedPackages;
    }
}
