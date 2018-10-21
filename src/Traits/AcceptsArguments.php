<?php

namespace DevMcC\PackageDev\Traits;

use DevMcC\PackageDev\Classes\Output;

/**
 * Trait AcceptsArguments
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Traits
 */
trait AcceptsArguments
{
    /**
     * The arguments this command requires.
     *
     * @return array
     */
    protected function arguments()
    {
        return [];
    }

    /**
     * Attempts to get the requested argument.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $argument
     * @param  bool $optional
     *
     * @return mixed
     */
    protected function argument(string $argument, bool $optional)
    {
        global $argv;

        $arguments = $this->arguments();

        if (!isset($arguments[$argument])) {
            Output::abort('Unable to determine argument.');
        }

        $return = $argv[$arguments[$argument]+1] ?? null;

        if (!$return && !$optional) {
            Output::abort('Argument "'.$argument.'" was not found.');
        }

        return $return;
    }
}
