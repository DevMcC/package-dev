<?php

namespace DevMcC\PackageDev\Extendables;

use DevMcC\PackageDev\Classes\Output;

/**
 * Class Command
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Extendables
 */
class Command
{
    /**
     * App base.
     *
     * @var string $appBase
     */
    protected $appBase;

    /**
     * Sets the appBase.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $msg
     */
    public function setappBase(string $appBase)
    {
        $this->appBase = $appBase;

        if (!is_file(realpath($this->appBase.'/composer.json')) || !is_dir(realpath($this->appBase.'/vendor/'))) {
            Output::abort('ERROR: Current directory is not a valid composer directory.');
        }
    }
}
