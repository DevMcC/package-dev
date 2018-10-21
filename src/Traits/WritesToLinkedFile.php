<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait WritesToLinkedFile
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait WritesToLinkedFile
{
    /**
     * Writes the linkedPackages array into a json object, with always a pretty print.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  array $linkedPackages
     *
     * @return void
     */
    protected function writeToLinkedFile(array $linkedPackages = null)
    {
        $linkedPackages = $linkedPackages ?? $this->linkedPackages;
        $write = json_encode($linkedPackages, JSON_PRETTY_PRINT);

        if (!$write) {
            Output::abort('ERROR: Could not compile to json.');
        }

        if (!file_put_contents($this->linkedPath, $write)) {
            Output::abort('ERROR: Could not write to package-dev.json.');
        }
    }
}
