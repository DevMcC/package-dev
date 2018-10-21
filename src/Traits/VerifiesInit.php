<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait VerifiesInit
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait VerifiesInit
{
    /**
     * Verifies if PackageDev is initialized.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    protected function verifyInit()
    {
        if (!is_file($this->linkedPath)) {
            Output::abort('ERROR: PackageDev is not initialized.');
        }
    }
}
