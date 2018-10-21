<?php

namespace DevMcC\PackageDev\Core;

/**
 * Class Main
 *
 * @author  DevMcC <sinbox.c@gmail.com>
 *
 * @package DevMcC\PackageDev\Core
 */
class Main
{
    /**
     * App base, also known as the cwd.
     *
     * @var string $appBase
     */
    private $appBase;

    /**
     * PackageDev root.
     *
     * @var string $pdRoot
     */
    private $pdRoot;

    /**
     * Class constructor.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  string $appBase
     * @param  string $pdRoot
     *
     * @return void
     */
    public function __construct(string $appBase, string $pdRoot)
    {
        $this->appBase = $appBase;
        $this->pdRoot = $pdRoot;
    }

    /**
     * Runs the core of the PackageDev console.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @return void
     */
    public function run()
    {
        global $argv;

        $this->load([
            'Classes',
            'Extendables',
            'Traits',
            'Commands',
            'Config',
        ]);

        $command = \DevMcC\PackageDev\Config\CommandsConfig::config($argv[1]);

        if (!$command) {
            \DevMcC\PackageDev\Classes\Output::abort('Command not found.');
        }

        $runCommand = new $command();
        $runCommand->setappBase($this->appBase);
        $runCommand->handle();
    }

    /**
     * Loads an array of directories.
     *
     * @author DevMcC <sinbox.c@gmail.com>
     *
     * @param  array $loadArray
     *
     * @return void
     */
    private function load(array $loadArray)
    {
        foreach ($loadArray as $loadDir) {
            $realDir = realpath($this->pdRoot.$loadDir);

            if (!$realDir) {
                throw new \Exception();
            }

            $files = scandir(realpath($realDir));

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    require_once($realDir.'/'.$file);
                }
            }
        }
    }
}
